<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Http\Requests\StorePrescriptionRequest;
use App\Http\Requests\UpdatePrescriptionRequest;

class PrescriptionController extends Controller
{
    public function index()
    {
        $prescriptions = Prescription::with(['doctor', 'patient'])->get();
        return response()->json($prescriptions);
    }

    public function store(StorePrescriptionRequest $request)
    {
        $prescription = new Prescription();
        $prescription->fill($request->all());
        $prescription->save();
        return response()->json($prescription, 200);
    }

    public function show(string $id)
    {
        return Prescription::with(['doctor', 'patient'])->findOrFail($id);
    }

    public function update(UpdatePrescriptionRequest $request, string $id)
    {
        $prescription = Prescription::findOrFail($id);
        $prescription->fill($request->all());
        $prescription->save();
        return response()->json($prescription, 200);
    }

    public function destroy(string $id)
    {
        $prescription = Prescription::findOrFail($id);
        $prescription->delete();
        return response()->json(null, 200);
    }
}
