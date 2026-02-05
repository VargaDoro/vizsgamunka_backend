<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;

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
}
