<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Personal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        //
    }

    public function login()
    {
        return view('auth.login');
    }

    public function loginVerify(Request $request)
    {
        $credentials = $request->validate([
            'user' => 'required|string',
            'password' => 'required|min:4'
        ]);

        if (Auth::attempt(['user' => $credentials['user'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'user' => 'Las credenciales no coinciden.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/auth/login');
    }
}
