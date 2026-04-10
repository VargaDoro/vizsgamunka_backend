<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function doctorIndex()
    {
        $doctorId = Auth::id();
        $appointments = Appointment::with('patient:id,name,email')
            ->where('doctor_id', $doctorId)
            ->get()
            ->map(function($appt){
                return [
                    'id' => $appt->id,
                    'patient_name' => $appt->patient->name,
                    'appointment_time' => $appt->appointment_time,
                    'status' => $appt->status,
                ];
            });

        return response()->json($appointments);
    }

    public function patientIndex(Request $request)
    {
        $patientId = Auth::id();

        $order = $request->query('order', 'asc');
        if (! in_array($order, ['asc', 'desc'])) {
            $order = 'asc';
        }

        $appointments = Appointment::with('doctor.officeLocation')
            ->where('patient_id', $patientId)
            ->orderBy('appointment_time', $order)
            ->get()
            ->map(function ($appt) {
                return [
                    'id' => $appt->id,
                    'doctor_name' => $appt->doctor->name,
                    'specialization' => $appt->doctor->specialization,
                    'office_location' => $appt->doctor->officeLocation
                        ? $appt->doctor->officeLocation->building . ' ' . $appt->doctor->officeLocation->room_number
                        : 'Nincs megadva',
                    'appointment_time' => $appt->appointment_time,
                    'status' => $appt->status,
                ];
            });

        return response()->json($appointments);
    }

    public function patientDestroy($id)
    {
        $patientId = Auth::id();
        $appointment = Appointment::where('patient_id', $patientId)->findOrFail($id);
        $appointment->delete();
        return response()->json(null, 200);
    }

    public function doctorAppointmentsForPatient($doctor_id)
    {
        $appointments = Appointment::where('doctor_id', $doctor_id)
            ->with('patient:id,name')
            ->get()
            ->map(function ($appt) {
                return [
                    'id' => $appt->id,
                    'doctor_id' => $appt->doctor_id,
                    'patient_id' => $appt->patient_id,
                    'appointment_time' => $appt->appointment_time,
                    'status' => $appt->status,
                    'patient' => $appt->patient,
                ];
            });

        return response()->json($appointments);
    }
    public function patientBookAppointment(Request $request, $doctor_id)
    {
        $validated = $request->validate([
            'appointment_time' => 'required|date|after:now',
        ]);

        $patient_id = $request->user()->id;

        // Ütközés
        $exists = Appointment::where('doctor_id', $doctor_id)
            ->where('appointment_time', $validated['appointment_time'])
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Ez az időpont foglalt!'], 409);
        }

        $appointment = Appointment::create([
            'doctor_id' => $doctor_id,
            'patient_id' => $patient_id,
            'appointment_time' => $validated['appointment_time'],
            'status' => 'scheduled',
        ]);

        return response()->json([
            'message' => 'Foglalás sikeres!',
            'appointment' => $appointment
        ], 201);
    }
}
