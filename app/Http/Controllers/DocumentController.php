<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Document_type;
use App\Models\User;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
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

    //////////////////main branchből Doctor
      public function doctorIndex()
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
    public function doctorStore(Request $request)
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

    public function doctorShow($id)
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


    /////////////////////////main branchből patient
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

    public function patienthow($id)
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
