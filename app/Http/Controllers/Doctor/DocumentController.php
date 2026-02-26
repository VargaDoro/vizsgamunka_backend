<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function index()
    {
        $doctorId = Auth::id();
        $documents = Document::with('patient:id,name,email','type:id,name')
            ->where('doctor_id', $doctorId)
            ->get()
            ->map(function($doc){
                return [
                    'id' => $doc->id,
                    'patient_name' => $doc->patient->name,
                    'type' => $doc->type->name,
                    'created_at' => $doc->created_at,
                ];
            });

        return response()->json($documents);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'type_id' => 'required|exists:document_types,id',
            'description' => 'nullable|string',
        ]);

        $document = Document::create([
            'doctor_id' => Auth::id(),
            'patient_id' => $validated['patient_id'],
            'type_id' => $validated['type_id'],
            'description' => $validated['description'] ?? null,
        ]);

        return response()->json($document, 201);
    }

    public function show($id)
    {
        $doctorId = Auth::id();
        $document = Document::with('patient:id,name,email','type:id,name')
            ->where('doctor_id', $doctorId)
            ->findOrFail($id);

        return response()->json([
            'id' => $document->id,
            'patient_name' => $document->patient->name,
            'type' => $document->type->name,
            'description' => $document->description,
            'created_at' => $document->created_at,
        ]);
    }
}
