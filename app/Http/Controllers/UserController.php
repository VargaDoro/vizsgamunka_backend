<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function show($id)
    {
        return User::find($id);
    }

    public function show_auth()
    {
        $user = Auth::user();
        return User::find($user->id);
    }

    public function store(Request $request)
    {
        $user = new User();
        $user->fill($request->all());

        $user->password = Hash::make($request->password);

        $user->save();
        return response()->json($user, 200);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        $user->fill($request->all());

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();
        return response()->json($user, 201);
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        return response()->json(null, 200);
    }

    public function updatePassword(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            "password" => 'string|min:3|max:50'
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => $validator->errors()->all()], 400);
        }
        $user = User::where("id", $id)->update([
            "password" => Hash::make($request->password),
        ]);
        return response()->json(["user" => $user]);
    }

    public function setCookie(Request $request){ 
        $minutes = 60; 
        /*$response = new Response('Set Cookie'); $response->withCookie(cookie($request->'name', 'MyValue', $minutes)); 
        return $response;
        */
        
        return response('Set Cookie')
            ->withCookie(cookie('name', 'MyValue', $minutes));


    }

    public function readCookie(Request $request)
    {
        /*
        $value = $request->cookie('my_cookie');
        return response()->json(['cookie_value' => $value]);
        */
        
        $value = $request->cookie('name');

                return response()->json([
                    'cookie_name'  => 'name',
                    'cookie_value' => $value,
                ]);


    }

    public function deleteCookie()
    {
        /*
        Cookie::queue(Cookie::forget('my_cookie'));
        return response('Cookie törölve');
        */
        
        return response('Delete Cookie')
                    ->withCookie(cookie()->forget('name'));

    }


    //////////////main branchből doctor
     public function doctorShowAuth()
    {
        $user = Auth::user();
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ]);
    }

    public function doctorIndex()
    {
        $doctor = Auth::user();
        $patients = $doctor->patients()->with('user:id,name,email')->get()->map(function($patient){
            return [
                'id' => $patient->id,
                'name' => $patient->user->name,
                'email' => $patient->user->email,
            ];
        });

        return response()->json($patients);
    }

    public function doctorUpdateAuth(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
        ]);

        $user->update($validated);

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function doctorUpdatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'password' => 'required|string|min:3|max:50',
        ]);

        $user->password = bcrypt($validated['password']);
        $user->save();

        return response()->json(['message' => 'Password updated successfully']);
    }
    /////////////////main branchből patient
      public function patientShowAuth()
    {
        $user = Auth::user();
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function patientUpdateAuth(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'password' => 'sometimes|string|min:6|max:50',
        ]);

        if(isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);
        return response()->json($user);
    }
}