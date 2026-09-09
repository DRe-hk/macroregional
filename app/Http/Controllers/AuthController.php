<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function mostrarLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'ADMIN') {
                return redirect()->route('admin.index');
            }

            return redirect()->route('delegado.index');
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'usuario' => ['required', 'string'],
            'clave' => ['required', 'string'],
        ], [
            'usuario.required' => 'El usuario es obligatorio.',
            'clave.required' => 'La contraseña es obligatoria.',
        ]);

        $remember = $request->boolean('remember', true);

        // Intentar autenticación por username
        if (Auth::attempt(['username' => $credentials['usuario'], 'password' => $credentials['clave'], 'activo' => true], $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->role === 'ADMIN') {
                return redirect()->intended(route('admin.index'))->with('success', '¡Bienvenido Administrador General!');
            }

            return redirect()->intended(route('delegado.index'))->with('success', '¡Bienvenido, '.$user->name.'!');
        }

        // Si falló, intentar también con email por si acaso
        if (Auth::attempt(['email' => $credentials['usuario'], 'password' => $credentials['clave'], 'activo' => true], $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->role === 'ADMIN') {
                return redirect()->intended(route('admin.index'))->with('success', '¡Bienvenido Administrador General!');
            }

            return redirect()->intended(route('delegado.index'))->with('success', '¡Bienvenido, '.$user->name.'!');
        }

        return back()
            ->withInput($request->only('usuario'))
            ->with('error', 'Credenciales incorrectas o cuenta inactiva. Verifica tus datos.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Has cerrado sesión correctamente.');
    }
}
