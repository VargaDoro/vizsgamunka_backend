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
}
