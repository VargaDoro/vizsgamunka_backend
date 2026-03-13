<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'id' => 'required|exists:users,id',
        ]);

        $patient = Patient::create($validated);

        return response()->json([
            'id' => $patient->id,
            'id' => $patient->id,
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
            'id' => 'sometimes|exists:users,id',
        ]);

        $patient->update($validated);

        return response()->json([
            'id' => $patient->id,
            'id' => $patient->id,
            'updated_at' => $patient->updated_at,
        ]);
    }

    public function adminDestroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();

        return response()->json(['message' => 'Patient deleted'], 200);
    }

    ///////////////////////////////main branchből patient
    public function patientShowAuth()
    {
        $patient = Patient::with('user:id,name,email,phone')->where('id', Auth::id())->firstOrFail();

        return response()->json([
            'id' => $patient->id,
            'name' => $patient->user->name,
            'email' => $patient->user->email,
            'phone' => $patient->user->phone,
            'birthdate' => $patient->birthdate,
            'gender' => $patient->gender,
        ]);
    }

    public function patientUpdateAuth(Request $request)
    {
        $patient = Patient::where('id', Auth::id())->firstOrFail();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'phone' => 'sometimes|string|max:50',
            'birthdate' => 'sometimes|date',
            'gender' => 'sometimes|string|max:20',
        ]);

        $patient->user->update($validated);
        return response()->json($patient);
    }
}
