<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::with(['patient', 'doctor', 'type'])->get();
        return response()->json($documents);
    }

    public function store(StoreDocumentRequest $request)
    {
        $document = new Document();
        $document->fill($request->all());
        $document->save();
        return response()->json($document, 200);
    }

    public function show(string $id)
    {
        return Document::with(['patient', 'doctor', 'type'])->findOrFail($id);
    }

    public function update(UpdateDocumentRequest $request, string $id)
    {
        $document = Document::findOrFail($id);
        $document->fill($request->all());
        $document->save();
        return response()->json($document, 200);
    }

    public function destroy(string $id)
    {
        $document = Document::findOrFail($id);
        $document->delete();
        return response()->json(null, 200);
    }

    
public function myDocuments()
    {
        $user = Auth::user();

        if (!$user->isUser()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $documents = Document::with(['doctor.user', 'type'])
            ->where('patient_id', $user->id) // megint: user_id a patient_id
            ->orderBy('created_at', 'desc')
            ->get();

        // GDPR: csak a szükséges mezők
        $result = $documents->map(function (Document $doc) {
            return [
                'id'         => $doc->id,
                'type'       => $doc->type?->type ?? $doc->type, // ha van DocumentType
                'created_at' => $doc->created_at,
                'doctor'     => $doc->doctor?->user?->name ?? null,
            ];
        });

        return response()->json($result);
    }

    /**
     * Egy dokumentum metaadatainak lekérése.
     * GET /api/documents/{document}
     */
    public function show(Document $document)
    {
        $user = Auth::user();

        if ($user->isUser() && $document->patient_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json([
            'id'         => $document->id,
            'type'       => $document->type?->type ?? $document->type,
            'created_at' => $document->created_at,
            'doctor'     => $document->doctor?->user?->name ?? null,
        ]);
    }

    /**
     * Dokumentum letöltése.
     * GET /api/documents/{document}/download
     */
    public function download(Document $document)
    {
        $user = Auth::user();

        if ($user->isUser() && $document->patient_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $filePath = storage_path('app/' . $document->file_path);

        if (!file_exists($filePath)) {
            return response()->json(['message' => 'File not found'], 404);
        }

        // Ha nincs külön tárolva az "eredeti név", használjuk a file_path utolsó részét
        $downloadName = basename($document->file_path);

        return response()->download($filePath, $downloadName);
    }

}
