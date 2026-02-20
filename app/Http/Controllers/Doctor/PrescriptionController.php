<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrescriptionController extends Controller
{
    public function index()
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

    public function store(Request $request)
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

    public function show($id)
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
