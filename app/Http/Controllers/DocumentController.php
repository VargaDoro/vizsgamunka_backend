<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Document_type;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function documentTypes()
    {
        $types = Document_type::query()
            ->select(['id', 'type'])
            ->orderBy('type')
            ->get();

        return response()->json($types);
    }

    public function upload(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|max:10240',
            'taj' => 'required|string|max:20',
            'type' => 'nullable|string|max:50',
            'document_type_id' => 'nullable|integer|exists:document_types,id',
            'date' => 'nullable|date',
        ]);

        $patient = User::where('role', 'patient')
            ->where('social_security_number', $validated['taj'])
            ->first();

        if (!$patient) {
            return response()->json([
                'message' => 'Nem talalhato beteg a megadott TAJ szammal.'
            ], 422);
        }

        $resolvedType = $validated['type'] ?? null;

        if (!empty($validated['document_type_id'])) {
            $docType = Document_type::find($validated['document_type_id']);
            $resolvedType = $docType?->type;
        }

        if (!$resolvedType) {
            return response()->json([
                'message' => 'A dokumentum tipusa kotelezo (type vagy document_type_id).'
            ], 422);
        }

        $storedPath = $request->file('file')->store('documents', 'public');

        $document = Document::create([
            'doctor_id' => Auth::id(),
            'patient_id' => $patient->id,
            'type' => $resolvedType,
            'file_path' => $storedPath,
            'created_at' => !empty($validated['date'])
                ? Carbon::parse($validated['date'])
                : now(),
        ]);

        return response()->json([
            'id' => $document->id,
            'doctor_id' => $document->doctor_id,
            'patient_id' => $document->patient_id,
            'type' => $document->type,
            'file_path' => $document->file_path,
            'created_at' => $document->created_at,
        ], 201);
    }

    public function patientIndex()
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
}
