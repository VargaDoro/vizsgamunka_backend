<?php

namespace App\Http\Controllers;

use App\Models\OfficeLocation;
use App\Http\Requests\StoreOfficeLocationRequest;
use App\Http\Requests\UpdateOfficeLocationRequest;

class OfficeLocationController extends Controller
{
    public function index()
    {
        $locations = OfficeLocation::with('doctors')->get();
        return response()->json($locations);
    }

    public function store(StoreOfficeLocationRequest $request)
    {
        $location = new OfficeLocation();
        $location->fill($request->all());
        $location->save();
        return response()->json($location, 200);
    }

    public function show(string $id)
    {
        return OfficeLocation::with('doctors')->findOrFail($id);
    }

    public function update(UpdateOfficeLocationRequest $request, string $id)
    {
        $location = OfficeLocation::findOrFail($id);
        $location->fill($request->all());
        $location->save();
        return response()->json($location, 200);
    }

    public function destroy(string $id)
    {
        $location = OfficeLocation::findOrFail($id);
        $location->delete();
        return response()->json(null, 200);
    }
}
