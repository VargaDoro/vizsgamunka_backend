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
        $response = new Response('Set Cookie'); $response->withCookie(cookie($request->'name', 'MyValue', $minutes)); 
        return $response; 
    }

    public function readCookie(Request $request)
    {
        $value = $request->cookie('my_cookie');
        return response()->json(['cookie_value' => $value]);
    }

    public function deleteCookie()
    {
        Cookie::queue(Cookie::forget('my_cookie'));
        return response('Cookie törölve');
    }
}