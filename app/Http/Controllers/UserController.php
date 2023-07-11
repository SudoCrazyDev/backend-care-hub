<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('username', $request->username)->first();
        if (!$user) return response()->json(['message' => 'Invalid Credentials'], 404);
        if (!Hash::check($request->password, $user->password)) return response()->json(['message' => 'Invalid Credentials'], 404);
        return $user;
    }
}
