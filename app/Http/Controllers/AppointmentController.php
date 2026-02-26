<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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

    
public function myAppointments()
    {
        $user = Auth::user();

        if (!$user->isUser()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $appointments = Appointment::with(['doctor.user'])
            ->where('patient_id', $user->id) // itt a user_id-vel dolgozunk!
            ->orderBy('appointment_time', 'asc')
            ->get();

        return response()->json($appointments);
    }

    /**
     * Új időpont foglalása.
     * POST /api/appointments
     * body: { "doctor_id": 5, "appointment_time": "2026-03-01 10:00" }
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user->isUser()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            // fontos: a doctors táblában a primary key a user_id, erre kell "exists"
            'doctor_id'       => ['required', 'integer', 'exists:doctors,user_id'],
            'appointment_time'=> ['required', 'date', 'after:now'],
        ]);

        // Ellenőrizzük, hogy az adott orvosnál szabad-e ez az időpont
        $conflict = Appointment::where('doctor_id', $validated['doctor_id'])
            ->where('appointment_time', $validated['appointment_time'])
            ->where('status', 'booked')
            ->exists();

        if ($conflict) {
            return response()->json([
                'message' => 'The selected time slot is already booked.'
            ], 422);
        }

        $appointment = Appointment::create([
            'doctor_id'       => $validated['doctor_id'],
            'patient_id'      => $user->id, // user_id kerül ide
            'appointment_time'=> $validated['appointment_time'],
            'status'          => 'booked',
        ]);

        return response()->json($appointment, 201);
    }

    /**
     * Időpont lemondása.
     * DELETE /api/appointments/{appointment}
     */
    public function cancel(Appointment $appointment)
    {
        $user = Auth::user();

        if (!$user->isUser()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        // csak a saját időpontját mondhatja le
        if ($appointment->patient_id !== $user->id) {
            return response()->json(['message' => 'Not your appointment'], 403);
        }

        if ($appointment->status !== 'booked') {
            return response()->json(['message' => 'Cannot cancel this appointment'], 400);
        }

        $appointment->status = 'cancelled';
        $appointment->save();

        return response()->json(['message' => 'Appointment cancelled']);
    }
}
