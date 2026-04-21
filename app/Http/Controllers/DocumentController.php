<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Document_type;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        $request->merge([
            'document_type_id' => $request->input('document_type_id', $request->input('type_id')),
            'taj' => $request->input('taj', $request->input('social_security_number')),
        ]);

        $validated = $request->validate([
            'file' => 'required|file|max:10240',
            'taj' => 'required|string|max:20',
            'document_type_id' => 'required|integer|exists:document_types,id',
            'date' => 'nullable|date',
        ]);

        $patient = User::where('social_security_number', $validated['taj'])
            ->first();

        if (!$patient) {
            return response()->json([
                'message' => 'Nem talalhato beteg a megadott TAJ szammal.'
            ], 422);
        }


        $uploadedFile = $request->file('file');
        $storedPath = $request->file('file')->store('documents', 'public');

        $document = Document::create([
            'doctor_id' => Auth::id(),
            'patient_id' => $patient->id,
            'document_type_id' => $validated['document_type_id'],
            'file_path' => $storedPath,
            'created_at' => !empty($validated['date'])
                ? Carbon::parse($validated['date'])
                : now(),
        ]);

        return response()->json([
            'id' => $document->id,
            'doctor_id' => $document->doctor_id,
            'patient_id' => $document->patient_id,
            'document_type_id' => $document->document_type_id,
            'file_path' => $document->file_path,
            'created_at' => $document->created_at,
        ], 201);
    }

    

public function patientIndex()
{
    $patientId = Auth::id();

    $documents = Document::with(['doctor:id,name', 'documentType:id,type'])
        ->where('patient_id', $patientId)
        ->orderByDesc('created_at')
        ->get()
        ->map(function ($doc) {
            return [
                'id' => $doc->id,
                'type' => $doc->documentType?->type,
                'doctor_name' => $doc->doctor->name ?? 'Ismeretlen',
                'created_at' => $doc->created_at,
            ];
        });

    return response()->json($documents);
}
    
public function view($id)
{
    if (!Auth::check()) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }

    $document = Document::where('id', $id)
        ->where('patient_id', Auth::id())
        ->first();

    if (!$document) {
        return response()->json(['message' => 'Not found'], 404);
    }

    $path = Storage::disk('public')->path($document->file_path);

    if (!file_exists($path)) {
        return response()->json([
            'message' => 'A dokumentum fájl nem található.'
        ], 404);
    }

    return response()->file($path, [
        'Content-Disposition' => 'inline'
    ]);
}

}
