<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index()
    {
        $patientId = Auth::id();
        $appointments = Appointment::with('doctor:id,name,email')
            ->where('patient_id', $patientId)
            ->get()
            ->map(fn($appt) => [
                'id' => $appt->id,
                'doctor_name' => $appt->doctor->name,
                'scheduled_at' => $appt->scheduled_at,
                'status' => $appt->status,
            ]);

        return response()->json($appointments);
    }

    public function show($id)
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

    public function store(Request $request)
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

    public function destroy($id)
    {
        $patientId = Auth::id();
        $appointment = Appointment::where('patient_id', $patientId)->findOrFail($id);
        $appointment->delete();
        return response()->json(null, 200);
    }
}
