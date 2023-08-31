<?php

namespace App\Http\Controllers;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function authenticate(Request $request): JsonResponse
    {
        $credentials = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($credentials->fails()) {
            return response()->json([
                "Message" => "Nie poprawny format danych do logowania!",
                "Status" => "NO"
            ]);
        }

        $credentials = $credentials->validated();
        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
 
            return response()->json([
                "Message" => "",
                "Status" => "OK"
            ]);
        } 

        return response()->json([
            "Message" => "Podany użytkownik nie istnieje!",
            "Status" => "NO"
        ]);
    }

    public function authorization(): JsonResponse
    {
        $user = Auth::user();
        return response()->json([
            "User" => $user->name,
            "Surname" => $user->surname,
            "Role" => $user->role,
            "Auth" => "OK"
        ]);
    }
}
