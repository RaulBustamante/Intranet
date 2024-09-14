<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    // Mostrar el formulario de login
    public function showLoginForm()
    {
        return view('login');
    }

    // Manejar el login
    public function login(Request $request)
{
    $request->validate([
        'username' => 'required',
        'password' => 'required',
    ]);

    // Usuario y contraseña definidos en .env
    $user = env('AUTH_USER');
    $pass = env('AUTH_PASS');

    // Verificar si los datos de acceso son correctos
    if ($request->username === $user && $request->password === $pass) {
        // Establecer autenticación en la sesión
        $request->session()->put('authenticated', true);

        // Redirigir a la página que intentaba acceder antes de autenticarse
        if ($request->session()->has('redirect_to')) {
            return redirect($request->session()->pull('redirect_to'));
        }

        return redirect('/'); // O la página principal por defecto
    }

    return back()->withErrors(['login_error' => 'Usuario o contraseña incorrectos']);
}


    // Cerrar sesión
    public function logout(Request $request)
{
    // Eliminar la sesión autenticada
    $request->session()->forget('authenticated');

    // Redirigir a la página de login
    return redirect()->route('welcome');
}

}
