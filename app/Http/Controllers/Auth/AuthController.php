<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $response = Http::post(env('CORE_SERVICE_URL') . 'login', [
            'email'    => $request->email,
            'password' => $request->password,
        ]);

        if ($response->failed()) {
            return back()->withErrors(['message' => 'Invalid credentials.']);
        }

        $data = $response->json();
        session([
            'jwt_token' => $data['token'],
            'user'      => $data['user']
        ]);

        return redirect()->route('ads.index');
    }

    public function logout(Request $request)
    {
//        $token = $request->bearerToken();
//
//        if (!$token) {
//            return response()->json(['message' => 'No token provided'], 401);
//        }
//
//        $response = Http::withToken($token)
//                        ->post(env('CORE_SERVICE_URL') . 'logout');
//
//        if ($response->successful()) {
//            return response()->json(['message' => 'Logged out successfully']);
//        }
//
//        return response()->json([
//            'message' => 'Logout failed or core service error'
//        ], $response->status());

        Session::forget(['jwt_token', 'user']);
        return redirect()->route('login');
    }
}
