<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PrescriptionController extends Controller
{
    public function index()
    {
        $prescriptions = Prescription::with('patient:id,name,email')
            ->where('doctor_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return response()->json($prescriptions);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => [
                'required',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', 'patient')),
            ],
            'medicine_name' => 'required|string|max:255',
            'dosage' => 'required|string|max:255',
            'issued_at' => 'required|date',
            'valid_until' => 'required|date|after_or_equal:issued_at',
        ]);

        $prescription = Prescription::create([
            'doctor_id' => Auth::id(),
            'patient_id' => $validated['patient_id'],
            'medicine_name' => $validated['medicine_name'],
            'dosage' => $validated['dosage'],
            'issued_at' => $validated['issued_at'],
            'valid_until' => $validated['valid_until'],
        ]);

        return response()->json($prescription, 201);
    }

    public function show(string $id)
    {
        $prescription = Prescription::with('patient:id,name,email')
            ->where('doctor_id', Auth::id())
            ->findOrFail($id);

        return response()->json($prescription);
    }

    public function update(Request $request, string $id)
    {
        $prescription = Prescription::where('doctor_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'patient_id' => [
                'sometimes',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', 'patient')),
            ],
            'medicine_name' => 'sometimes|string|max:255',
            'dosage' => 'sometimes|string|max:255',
            'issued_at' => 'sometimes|date',
            'valid_until' => 'sometimes|date',
        ]);

        if (isset($validated['valid_until']) && !isset($validated['issued_at'])) {
            $validated['issued_at'] = $prescription->issued_at;
        }

        if (isset($validated['issued_at']) && isset($validated['valid_until'])
            && $validated['valid_until'] < $validated['issued_at']) {
            return response()->json([
                'message' => 'A valid_until nem lehet korabbi, mint az issued_at.'
            ], 422);
        }

        $prescription->fill($validated);
        $prescription->save();

        return response()->json($prescription, 200);
    }

    public function destroy(string $id)
    {
        $prescription = Prescription::where('doctor_id', Auth::id())->findOrFail($id);
        $prescription->delete();

        return response()->json(null, 200);
    }
}
