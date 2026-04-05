<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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

    public function store(Request $request)
    {
        return $this->doctorStore($request);
    }

    public function show(string $id)
    {
        $patient = User::where('role', 'patient')
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
            ->findOrFail($id);

        return response()->json($patient);
    }

    public function update(Request $request, string $id)
    {
        $patient = User::where('role', 'patient')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
            'social_security_number' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('users', 'social_security_number')->ignore($patient->id),
            ],
            'birth_date' => 'nullable|date',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'street_address' => 'nullable|string|max:200',
            'phone_number' => 'nullable|string|max:20',
            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($patient->id),
            ],
            'password' => 'nullable|string|min:8',
        ]);

        if (array_key_exists('password', $validated) && $validated['password']) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['role'] = 'patient';

        $patient->fill($validated);
        $patient->save();

        return response()->json($patient, 200);
    }

    public function destroy(string $id)
    {
        $patient = User::where('role', 'patient')->findOrFail($id);
        $patient->delete();

        return response()->json(null, 200);
    }

    public function adminIndex()
    {
        return $this->index();
    }

    public function adminStore(Request $request)
    {
        return $this->store($request);
    }

    public function adminShow($id)
    {
        return $this->show($id);
    }

    public function adminUpdate(Request $request, $id)
    {
        return $this->update($request, $id);
    }

    public function adminDestroy($id)
    {
        return $this->destroy($id);
    }

    public function patientShowAuth()
    {
        $patient = User::where('role', 'patient')
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
            ])
            ->findOrFail(Auth::id());

        return response()->json($patient);
    }

    public function patientUpdateAuth(Request $request)
    {
        $patient = User::where('role', 'patient')->findOrFail(Auth::id());

        $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
            'social_security_number' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('users', 'social_security_number')->ignore($patient->id),
            ],
            'birth_date' => 'nullable|date',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'street_address' => 'nullable|string|max:200',
            'phone_number' => 'nullable|string|max:20',
            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($patient->id),
            ],
            'password' => 'nullable|string|min:8',
        ]);

        if (array_key_exists('password', $validated) && $validated['password']) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $patient->update($validated);

        return response()->json($patient);
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

        return response()->json($patient);
    }
}
