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

        // 1. SuperAdmin siempre pasa
        if ($user->rol === 'super_admin' || (method_exists($user, 'hasRole') && $user->hasRole('super_admin'))) {
            return $next($request);
        }

        $rolesAllowed = [];
        $cargosAllowed = [];

        // Parsear 'roles=super_admin,admin|cargos=recursos_humanos,asistente_rrhh'
        $segments = explode('|', $params);
        foreach ($segments as $segment) {
            $segment = trim($segment);
            if (str_starts_with($segment, 'roles=')) {
                $rolesStr = str_replace('roles=', '', $segment);
                $rolesAllowed = array_map('trim', explode(',', $rolesStr));
            }
            if (str_starts_with($segment, 'cargos=')) {
                $cargosStr = str_replace('cargos=', '', $segment);
                $cargosAllowed = array_map('trim', explode(',', $cargosStr));
            }
        }

        // 2. Comprobar Roles
        if (!empty($rolesAllowed)) {
            if (in_array($user->rol, $rolesAllowed)) {
                return $next($request);
            }
            if ($user->roles()->whereIn('nombre', $rolesAllowed)->exists()) {
                return $next($request);
            }
        }

        // 3. Comprobar Cargos (Verifica en columna estática Y en la relación pivote)
        if (!empty($cargosAllowed)) {
            if (in_array($user->cargo, $cargosAllowed)) {
                return $next($request);
            }
            if ($user->cargos()->whereIn('nombre', $cargosAllowed)->exists()) {
                return $next($request);
            }
        }

        // Si nada coincide, abortar con 403
        abort(403, 'No tienes el rol ni el cargo requerido para acceder.');
    }
}