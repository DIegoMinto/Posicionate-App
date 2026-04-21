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

    // En AuthController.php

    public function loginVerify(Request $request)
    {
        $credentials = $request->validate([
            'user' => 'required|string',
            'password' => 'required|min:4'
        ]);

        // Intentamos loguear buscando por la columna 'user'
        if (Auth::attempt(['user' => $credentials['user'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'user' => 'Las credenciales no coinciden.',
        ]);
    }
}
