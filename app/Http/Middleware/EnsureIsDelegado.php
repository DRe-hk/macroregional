<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsDelegado
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para acceder al portal de delegados.');
        }

        if (auth()->user()->role === 'ADMIN') {
            return redirect()->route('admin.index')->with('info', 'Sesión de Administrador detectada. Has sido redirigido al panel administrativo.');
        }

        if (auth()->user()->role !== 'DELEGADO' || ! auth()->user()->activo) {
            abort(403, 'Acceso denegado o cuenta inactiva.');
        }

        return $next($request);
    }
}
