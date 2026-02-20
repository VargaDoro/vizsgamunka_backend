<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Http\Requests\StoreDoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with(['user', 'officeLocation'])->get();
        return response()->json($doctors);
    }

    public function store(StoreDoctorRequest $request)
    {
        $doctor = new Doctor();
        $doctor->fill($request->all());
        $doctor->save();
        return response()->json($doctor, 200);
    }

    public function show(string $id)
    {
        return Doctor::with(['user', 'officeLocation'])->findOrFail($id);
    }

    public function update(UpdateDoctorRequest $request, string $id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->fill($request->all());
        $doctor->save();
        return response()->json($doctor, 200);
    }

    public function destroy(string $id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->delete();
        return response()->json(null, 200);
    }
}
