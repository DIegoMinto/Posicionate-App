<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleOrCargo
{
    public function handle(Request $request, Closure $next, string $params): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->rol === 'super_admin' || (method_exists($user, 'hasRole') && $user->hasRole('super_admin'))) {
            return $next($request);
        }

        $rolesAllowed = [];
        $cargosAllowed = [];

        $segments = explode('|', $params);
        foreach ($segments as $segment) {
            $segment = trim($segment);
            if (str_starts_with($segment, 'roles=')) {
                $rolesStr = str_replace('roles=', '', $segment);
                $rolesAllowed = array_map('trim', explode('+', $rolesStr));
            }
            if (str_starts_with($segment, 'cargos=')) {
                $cargosStr = str_replace('cargos=', '', $segment);
                $cargosAllowed = array_map('trim', explode('+', $cargosStr));
            }
        }

        if (!empty($rolesAllowed) && $user->hasAnyRole($rolesAllowed)) {
            return $next($request);
        }

        if (!empty($cargosAllowed) && $user->hasAnyCargo($cargosAllowed)) {
            return $next($request);
        }

        abort(403, 'No tienes el rol ni el cargo requerido para acceder.');
    }
}