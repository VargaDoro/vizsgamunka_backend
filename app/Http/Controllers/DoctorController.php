<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreDoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'doctor')
            ->select([
                'id',
                'name',
                'email',
                'phone_number',
                'specialization',
                'office_location_id',
            ]);

        // SZAK SZERINTI SZŰRÉS
        if ($request->filled('specialization')) {
            $query->where('specialization', $request->query('specialization'));
        }

        $doctors = $query->get();

        return response()->json($doctors);
    }

    public function store(StoreDoctorRequest $request)
    {
        $doctor = new User();
        $doctor->fill($request->all());
        $doctor->save();
        return response()->json($doctor, 200);
    }


    public function show($id)
    {
        $doctor = User::where('role', 'doctor')
            ->with('officeLocation')
            ->findOrFail($id);

        return response()->json([
            'id' => $doctor->id,
            'name' => $doctor->name,
            'email' => $doctor->email,
            'specialization' => $doctor->specialization,
            'phone_number' => $doctor->phone_number,
            'office_location' => $doctor->officeLocation
                ? $doctor->officeLocation->building . ' - ' . $doctor->officeLocation->room_number
                : null,
        ]);
    }

    public function update(UpdateDoctorRequest $request, string $id)
    {
        $doctor = User::findOrFail($id);
        $doctor->fill($request->all());
        $doctor->save();
        return response()->json($doctor, 200);
    }

    public function destroy(string $id)
    {
        $doctor = User::findOrFail($id);
        $doctor->delete();
        return response()->json(null, 200);
    }

    public function specializations()
    {
        // Csak orvosok, ahol a specialization nem null/üres
        $specializations = User::where('role', 'doctor')
            ->whereNotNull('specialization')
            ->where('specialization', '!=', '')
            ->select('specialization')
            ->distinct()
            ->orderBy('specialization')
            ->pluck('specialization');

        return response()->json($specializations);
    }
}
