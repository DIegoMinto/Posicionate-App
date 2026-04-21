<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckVigencia
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Auth::user() aquí ya es una instancia del modelo Personal
        if (auth()->check()) {
            $usuarioLogueado = auth()->user();

            // Verificamos el campo directamente en el modelo Personal
            if (!$usuarioLogueado->es_vigente) {
                auth()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'user' => 'Tu cuenta de personal no se encuentra vigente.'
                ]);
            }
        }

        return $next($request);
    }
}
