<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleOrCargo
{
    public function handle(Request $request, Closure $next, ...$params): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (method_exists($user, 'hasRole') && $user->hasRole('super_admin')) {
            return $next($request);
        }

        $fullExpression = implode(',', $params);

        $rolesAllowed = [];
        $cargosAllowed = [];

        $segments = explode('|', $fullExpression);
        foreach ($segments as $segment) {
            $segment = trim($segment);

            if (str_starts_with($segment, 'roles=')) {
                $rolesStr = str_replace('roles=', '', $segment);
                $rolesAllowed = array_filter(array_map('trim', preg_split('/[+,]/', $rolesStr)));
            }

            if (str_starts_with($segment, 'cargos=')) {
                $cargosStr = str_replace('cargos=', '', $segment);
                $cargosAllowed = array_filter(array_map('trim', preg_split('/[+,]/', $cargosStr)));
            }
        }

        if (!empty($rolesAllowed) && $user->hasAnyRole($rolesAllowed)) {
            return $next($request);
        }

        if (!empty($cargosAllowed) && $user->hasAnyCargo($cargosAllowed)) {
            return $next($request);
        }

        dd([
            'usuario_id' => $user->id_personal,
            'cargos_en_BD' => $user->cargos->pluck('nombre')->toArray(),
            'cargos_permitidos' => $cargosAllowed,
            'evaluacion' => $user->hasAnyCargo($cargosAllowed),
        ]);

        abort(403, 'No tienes el rol ni el cargo requerido para acceder.');
    }
}