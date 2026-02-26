<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::with('user')->get();
        return response()->json($patients);
    }

    public function store(StorePatientRequest $request)
    {
        $patient = new Patient();
        $patient->fill($request->all());
        $patient->save();
        return response()->json($patient, 200);
    }

    public function show(string $id)
    {
        return Patient::with('user')->findOrFail($id);
    }

    public function update(UpdatePatientRequest $request, string $id)
    {
        $patient = Patient::findOrFail($id);
        $patient->fill($request->all());
        $patient->save();
        return response()->json($patient, 200);
    }

    public function destroy(string $id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();
        return response()->json(null, 200);
    }


    public function me()
    {
        $user = Auth::user();

        // Csak "normál" user (patient) használhatja
        if (!$user->isUser()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $patient = Patient::with('user')
            ->where('user_id', $user->id)
            ->firstOrFail();

        // GDPR: csak a szükséges adatokat küldjük
        return response()->json([
            'id'            => $patient->user_id,
            'name'          => $patient->name,
            'birth_date'    => $patient->birth_date,
            'phone_number'  => $patient->phone_number,
            'address'       => [
                'country'       => $patient->country,
                'city'          => $patient->city,
                'postal_code'   => $patient->postal_code,
                'street_address' => $patient->street_address,
            ],
            'user' => [
                'email' => $patient->user->email,
            ],
            // 'social_security_number' -et is visszaadhatod, ha a UI-hoz kell
        ]);
    }
}
