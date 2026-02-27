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

    //////////////// main branchből doctor
     public function doctorIndex()
    {
        $doctorId = Auth::id();
        $prescriptions = Prescription::with('patient:id,name,email')
            ->where('doctor_id', $doctorId)
            ->get()
            ->map(function($prescription){
                return [
                    'id' => $prescription->id,
                    'patient_name' => $prescription->patient->name,
                    'medication' => $prescription->medication,
                    'dosage' => $prescription->dosage,
                    'created_at' => $prescription->created_at,
                ];
            });

        return response()->json($prescriptions);
    }

     public function doctorStore(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'medication' => 'required|string',
            'dosage' => 'required|string',
        ]);

        $prescription = Prescription::create([
            'doctor_id' => Auth::id(),
            'patient_id' => $validated['patient_id'],
            'medication' => $validated['medication'],
            'dosage' => $validated['dosage'],
        ]);

        return response()->json($prescription, 201);
    }

     public function doctorShow($id)
    {
        $doctorId = Auth::id();
        $prescription = Prescription::with('patient:id,name,email')
            ->where('doctor_id', $doctorId)
            ->findOrFail($id);

        return response()->json([
            'id' => $prescription->id,
            'patient_name' => $prescription->patient->name,
            'medication' => $prescription->medication,
            'dosage' => $prescription->dosage,
            'created_at' => $prescription->created_at,
        ]);
    }
}
