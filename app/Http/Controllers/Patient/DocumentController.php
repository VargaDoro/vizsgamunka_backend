<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function index()
    {
        $patientId = Auth::id();
        $documents = Document::with('doctor:id,name,email','type:id,name')
            ->where('patient_id', $patientId)
            ->get()
            ->map(fn($doc) => [
                'id' => $doc->id,
                'doctor_name' => $doc->doctor->name,
                'type' => $doc->type->name,
                'description' => $doc->description,
                'created_at' => $doc->created_at,
            ]);

        return response()->json($documents);
    }

    public function show($id)
    {
        $patientId = Auth::id();
        $document = Document::with('doctor:id,name,email','type:id,name')
            ->where('patient_id', $patientId)
            ->findOrFail($id);

        return response()->json([
            'id' => $document->id,
            'doctor_name' => $document->doctor->name,
            'type' => $document->type->name,
            'description' => $document->description,
            'created_at' => $document->created_at,
        ]);
    }
}
