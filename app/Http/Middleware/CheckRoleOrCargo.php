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

        $rolesAllowed = [];
        $cargosAllowed = [];

        // Parsear la cadena 'roles=admin,super_admin|cargos=gerente_marketing'
        $segments = explode('|', $params);
        foreach ($segments as $segment) {
            if (str_starts_with($segment, 'roles=')) {
                $rolesAllowed = explode(',', str_replace('roles=', '', $segment));
            }
            if (str_starts_with($segment, 'cargos=')) {
                $cargosAllowed = explode(',', str_replace('cargos=', '', $segment));
            }
        }

        $hasRole = !empty($rolesAllowed) && $user->hasAnyRole($rolesAllowed);
        $hasCargo = !empty($cargosAllowed) && $user->hasAnyCargo($cargosAllowed);

        // Pasa si tiene al menos UN rol o UN cargo permitido
        if (!($hasRole || $hasCargo)) {
            abort(403, 'No tienes el rol ni el cargo requerido para acceder.');
        }

        return $next($request);
    }
}