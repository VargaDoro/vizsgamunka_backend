<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $user->save();
        return reponse()->json($user, 200);
    }

    public function update(Request $request, $id)
    {
        $user = User()::find($id);
        $user->fill($request->all());
        $user->save();
        return reponse()->json($user, 201);
    }

    public function destroy($id)
    {
        $user = User()::find($id)->delete();
        return reponse()->json(null, 200);
    }        
}