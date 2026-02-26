<?php

namespace App\Http\Controllers;

use App\Models\DocumentType;
use App\Http\Requests\StoreDocumentTypeRequest;
use App\Http\Requests\UpdateDocumentTypeRequest;

class DocumentTypeController extends Controller
{
    public function index()
    {
        $types = DocumentType::with('documents')->get();
        return response()->json($types);
    }

    public function store(StoreDocumentTypeRequest $request)
    {
        $type = new DocumentType();
        $type->fill($request->all());
        $type->save();
        return response()->json($type, 200);
    }

    public function show(string $id)
    {
        return DocumentType::with('documents')->findOrFail($id);
    }

    public function update(UpdateDocumentTypeRequest $request, string $id)
    {
        $type = DocumentType::findOrFail($id);
        $type->fill($request->all());
        $type->save();
        return response()->json($type, 200);
    }

    public function destroy(string $id)
    {
        $type = DocumentType::findOrFail($id);
        $type->delete();
        return response()->json(null, 200);
    }
}
