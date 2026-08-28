<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCargo
{
    public function handle(Request $request, Closure $next, ...$cargos): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        if (!$request->user()->hasAnyCargo($cargos)) {
            abort(403, 'Tu cargo no tiene permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}