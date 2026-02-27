<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['doctor', 'patient'])->get();
        return response()->json($appointments);
    }

    public function store(StoreAppointmentRequest $request)
    {
        $appointment = new Appointment();
        $appointment->fill($request->all());
        $appointment->save();
        return response()->json($appointment, 200);
    }

    public function show(string $id)
    {
        return Appointment::with(['doctor', 'patient'])->findOrFail($id);
    }

    public function update(UpdateAppointmentRequest $request, string $id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->fill($request->all());
        $appointment->save();
        return response()->json($appointment, 200);
    }

    public function destroy(string $id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();
        return response()->json(null, 200);
    }
/////////////////////// main branchből Doctor
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
                    'scheduled_at' => $appt->scheduled_at,
                    'status' => $appt->status,
                ];
            });

        return response()->json($appointments);
    }

     public function doctorStore(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'scheduled_at' => 'required|date',
            'status' => 'nullable|string',
        ]);

        $appointment = Appointment::create([
            'doctor_id' => Auth::id(),
            'patient_id' => $validated['patient_id'],
            'scheduled_at' => $validated['scheduled_at'],
            'status' => $validated['status'] ?? 'scheduled',
        ]);

        return response()->json($appointment, 201);
    }

     public function doctorShow($id)
    {
        $doctorId = Auth::id();
        $appointment = Appointment::with('patient:id,name,email')
            ->where('doctor_id', $doctorId)
            ->findOrFail($id);

        return response()->json([
            'id' => $appointment->id,
            'patient_name' => $appointment->patient->name,
            'scheduled_at' => $appointment->scheduled_at,
            'status' => $appointment->status,
        ]);
    }


}
