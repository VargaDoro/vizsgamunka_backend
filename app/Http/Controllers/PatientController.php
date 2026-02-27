<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::with('user')->get();
        return response()->json($patients);
    }

    public function store(StorePatientRequest $request)
    {
        $patient = new Patient();
        $patient->fill($request->all());
        $patient->save();
        return response()->json($patient, 200);
    }

    public function show(string $id)
    {
        return Patient::with('user')->findOrFail($id);
    }

    public function update(UpdatePatientRequest $request, string $id)
    {
        $patient = Patient::findOrFail($id);
        $patient->fill($request->all());
        $patient->save();
        return response()->json($patient, 200);
    }

    public function destroy(string $id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();
        return response()->json(null, 200);
    }
///////////////////////////////main branchből admin
     public function adminIndex()
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

     public function adminStore(Request $request)
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

    public function adminShow($id)
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


    
    public function adminUpdate(Request $request, $id)
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

    public function adminDestroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();

        return response()->json(['message' => 'Patient deleted'], 200);
    }

}
