<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOfficeLocalationRequest;
use App\Http\Requests\UpdateOfficeLocalationRequest;
use App\Models\OfficeLocation;

class OfficeLocationController extends Controller
{
    public function index()
    {
        $locations = OfficeLocation::with('doctors')->get();
        return response()->json($locations);
    }

    public function store(StoreOfficeLocalationRequest $request)
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

    public function update(UpdateOfficeLocalationRequest $request, string $id)
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
