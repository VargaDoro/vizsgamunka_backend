<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentTypeRequest;
use App\Http\Requests\UpdateDocumentTypeRequest;
use App\Models\Document_type;

class DocumentTypeController extends Controller
{
    public function index()
    {
        $types = Document_type::with('documents')->get();
        return response()->json($types);
    }

    public function store(StoreDocumentTypeRequest $request)
    {
        $type = new Document_type();
        $type->fill($request->all());
        $type->save();
        return response()->json($type, 200);
    }

    public function show(string $id)
    {
        return Document_type::with('documents')->findOrFail($id);
    }

    public function update(UpdateDocumentTypeRequest $request, string $id)
    {
        $type = Document_type::findOrFail($id);
        $type->fill($request->all());
        $type->save();
        return response()->json($type, 200);
    }

    public function destroy(string $id)
    {
        $type = Document_type::findOrFail($id);
        $type->delete();
        return response()->json(null, 200);
    }
}
