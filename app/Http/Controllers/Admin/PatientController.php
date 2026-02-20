<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    // Admin csak adminisztratív adatokat láthat
    public function index()
    {
        // Csak a szükséges mezők visszaadása
        $patients = Patient::with('user:id,name,email,role')
            ->get()
            ->map(function ($patient) {
                return [
                    'id' => $patient->id,
                    'name' => $patient->user->name,
                    'email' => $patient->user->email,
                    'role' => $patient->user->role,
                    'created_at' => $patient->created_at,
                ];
            });

        return response()->json($patients);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $patient = Patient::create($validated);

        return response()->json([
            'id' => $patient->id,
            'user_id' => $patient->user_id,
            'created_at' => $patient->created_at,
        ], 200);
    }

    public function show($id)
    {
        $patient = Patient::with('user:id,name,email,role')->findOrFail($id);

        return response()->json([
            'id' => $patient->id,
            'name' => $patient->user->name,
            'email' => $patient->user->email,
            'role' => $patient->user->role,
            'created_at' => $patient->created_at,
        ]);
    }

    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'sometimes|exists:users,id',
        ]);

        $patient->update($validated);

        return response()->json([
            'id' => $patient->id,
            'user_id' => $patient->user_id,
            'updated_at' => $patient->updated_at,
        ]);
    }

    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();

        return response()->json(['message' => 'Patient deleted'], 200);
    }
}
