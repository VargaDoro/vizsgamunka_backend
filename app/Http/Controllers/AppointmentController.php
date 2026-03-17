<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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

    /////////////// main branchből Patient
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

    public function patientShow($id)
    {
        $patientId = Auth::id();
        $appointment = Appointment::with('doctor:id,name,email')
            ->where('patient_id', $patientId)
            ->findOrFail($id);

        return response()->json([
            'id' => $appointment->id,
            'doctor_name' => $appointment->doctor->name,
            'scheduled_at' => $appointment->scheduled_at,
            'status' => $appointment->status,
        ]);
    }

    public function patientStore(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'scheduled_at' => 'required|date',
        ]);

        $appointment = Appointment::create([
            'doctor_id' => $validated['doctor_id'],
            'patient_id' => Auth::id(),
            'scheduled_at' => $validated['scheduled_at'],
            'status' => 'scheduled',
        ]);

        return response()->json($appointment, 201);
    }

    public function patientDestroy($id)
    {
        $patientId = Auth::id();
        $appointment = Appointment::where('patient_id', $patientId)->findOrFail($id);
        $appointment->delete();
        return response()->json(null, 200);
    }

    //////// beteg időpontot foglal orvoshoz funkció ////////

    //Orvos időpontjainak lekérése
    public function doctorAppointmentsForPatient($doctor_id)
    {
        $appointments = Appointment::where('doctor_id', $doctor_id)
            ->with('patient:id,name,email')
            ->get();

        return response()->json($appointments);
    }

    //Időpont foglalása adott orvoshoz
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
