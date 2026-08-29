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

        // 1. SuperAdmin siempre tiene acceso total
        if ($user->rol === 'super_admin' || (method_exists($user, 'hasRole') && $user->hasRole('super_admin'))) {
            return $next($request);
        }

        $rolesAllowed = [];
        $cargosAllowed = [];

        // Parsear la cadena 'roles=admin,super_admin|cargos=recursos_humanos,asistente_rrhh'
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

        // 2. Verificar Roles (vía método hasAnyRole O columna estática 'rol')
        $hasRole = false;
        if (!empty($rolesAllowed)) {
            $hasRoleInPivot = method_exists($user, 'hasAnyRole') && $user->hasAnyRole($rolesAllowed);
            $hasRoleInColumn = in_array($user->rol, $rolesAllowed);
            $hasRole = $hasRoleInPivot || $hasRoleInColumn;
        }

        // 3. Verificar Cargos (vía método hasAnyCargo O columna estática 'cargo')
        $hasCargo = false;
        if (!empty($cargosAllowed)) {
            $hasCargoInPivot = method_exists($user, 'hasAnyCargo') && $user->hasAnyCargo($cargosAllowed);
            $hasCargoInColumn = in_array($user->cargo, $cargosAllowed);
            $hasCargo = $hasCargoInPivot || $hasCargoInColumn;
        }

        // Pasa si cumple al menos UN rol o UN cargo
        if (!($hasRole || $hasCargo)) {
            abort(403, 'No tienes el rol ni el cargo requerido para acceder.');
        }

        return $next($request);
    }
}