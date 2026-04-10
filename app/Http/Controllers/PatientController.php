<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
{
    public function index()
    {
        $patients = User::where('role', 'patient')
            ->select([
                'id',
                'name',
                'email',
                'social_security_number',
                'birth_date',
                'country',
                'city',
                'postal_code',
                'street_address',
                'phone_number',
                'created_at',
            ])
            ->get();

        return response()->json($patients);
    }

    public function doctorIndex()
    {
        /** @var User $doctor */
        $doctor = Auth::user();

        $patients = $doctor->patients()
            ->select([
                'users.id',
                'users.name',
                'users.email',
                'users.social_security_number',
                'users.birth_date',
                'users.country',
                'users.city',
                'users.postal_code',
                'users.street_address',
                'users.phone_number',
            ])
            ->get();

        return response()->json($patients);
    }

    public function doctorStore(Request $request)
    {
        $request->merge([
            // Support legacy frontend field name.
            'social_security_number' => $request->input('social_security_number', $request->input('taj')),
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'social_security_number' => 'nullable|string|max:20|unique:users,social_security_number',
            'birth_date' => 'nullable|date',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'street_address' => 'nullable|string|max:200',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'nullable|string|min:8',
        ]);

        // Empty string should behave as null for unique nullable fields.
        if (isset($validated['social_security_number']) && trim((string) $validated['social_security_number']) === '') {
            $validated['social_security_number'] = null;
        }

        $patient = User::create([
            'name' => $validated['name'],
            'social_security_number' => $validated['social_security_number'] ?? null,
            'birth_date' => $validated['birth_date'] ?? null,
            'country' => $validated['country'] ?? null,
            'city' => $validated['city'] ?? null,
            'postal_code' => $validated['postal_code'] ?? null,
            'street_address' => $validated['street_address'] ?? null,
            'phone_number' => $validated['phone_number'] ?? null,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'] ?? 'password123'),
            'role' => 'patient',
        ]);

        return response()->json([
            'id' => $patient->id,
            'name' => $patient->name,
            'social_security_number' => $patient->social_security_number,
            'birth_date' => $patient->birth_date,
            'country' => $patient->country,
            'city' => $patient->city,
            'postal_code' => $patient->postal_code,
            'street_address' => $patient->street_address,
            'phone_number' => $patient->phone_number,
            'email' => $patient->email,
            'role' => $patient->role,
            'created_at' => $patient->created_at,
        ], 201);
    }

    public function doctorShow($id)
    {
        /** @var User $doctor */
        $doctor = Auth::user();

        $patient = $doctor->patients()
            ->where('users.id', $id)
            ->select([
                'users.id',
                'users.name',
                'users.email',
                'users.social_security_number',
                'users.birth_date',
                'users.country',
                'users.city',
                'users.postal_code',
                'users.street_address',
                'users.phone_number',
            ])
            ->first();

        if (!$patient) {
            return response()->json([
                'message' => 'A páciens nem található, vagy nincs ehhez az orvoshoz időpontja.'
            ], 404);
        }

        $appointments = $patient->patientAppointments()
            ->where('doctor_id', $doctor->id)
            ->select(['id', 'appointment_time', 'status'])
            ->orderBy('appointment_time')
            ->get();

        return response()->json([
            'patient' => $patient,
            'appointments' => $appointments,
        ]);
    }
}
