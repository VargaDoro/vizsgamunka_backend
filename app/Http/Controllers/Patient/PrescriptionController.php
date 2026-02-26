<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use Illuminate\Support\Facades\Auth;

class PrescriptionController extends Controller
{
    public function index()
    {
        $patientId = Auth::id();
        $prescriptions = Prescription::with('doctor:id,name,email')
            ->where('patient_id', $patientId)
            ->get()
            ->map(fn($presc) => [
                'id' => $presc->id,
                'doctor_name' => $presc->doctor->name,
                'medication' => $presc->medication,
                'dosage' => $presc->dosage,
                'created_at' => $presc->created_at,
            ]);

        return response()->json($prescriptions);
    }

    public function show($id)
    {
        $patientId = Auth::id();
        $prescription = Prescription::with('doctor:id,name,email')
            ->where('patient_id', $patientId)
            ->findOrFail($id);

        return response()->json([
            'id' => $prescription->id,
            'doctor_name' => $prescription->doctor->name,
            'medication' => $prescription->medication,
            'dosage' => $prescription->dosage,
            'created_at' => $prescription->created_at,
        ]);
    }
}
