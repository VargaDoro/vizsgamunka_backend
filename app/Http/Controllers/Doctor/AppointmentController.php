<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index()
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

    public function store(Request $request)
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

    public function show($id)
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
