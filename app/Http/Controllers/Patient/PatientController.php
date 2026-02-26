<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function showAuth()
    {
        $patient = Patient::with('user:id,name,email,phone')->where('user_id', Auth::id())->firstOrFail();

        return response()->json([
            'id' => $patient->id,
            'name' => $patient->user->name,
            'email' => $patient->user->email,
            'phone' => $patient->user->phone,
            'birthdate' => $patient->birthdate,
            'gender' => $patient->gender,
        ]);
    }

    public function updateAuth(Request $request)
    {
        $patient = Patient::where('user_id', Auth::id())->firstOrFail();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'phone' => 'sometimes|string|max:50',
            'birthdate' => 'sometimes|date',
            'gender' => 'sometimes|string|max:20',
        ]);

        $patient->user->update($validated);
        return response()->json($patient);
    }
}
