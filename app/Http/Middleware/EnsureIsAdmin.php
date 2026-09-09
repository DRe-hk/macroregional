<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión con tu cuenta de administrador.');
        }

        if (auth()->user()->role !== 'ADMIN') {
            if (auth()->user()->role === 'DELEGADO') {
                return redirect()->route('delegado.index')->with('info', 'Sesión de delegado activa. Has sido redirigido a tu portal.');
            }
            abort(403, 'Acceso denegado. No tienes permisos de administrador general.');
        }

        return $next($request);
    }
}
