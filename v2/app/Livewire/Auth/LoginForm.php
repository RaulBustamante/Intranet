<?php

namespace App\Livewire\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Component;

/**
 * Login de la intranet (AUTH-01, AUTH-03, AUTH-04).
 *
 * Reemplaza la contraseña compartida de la v1 (un solo usuario, comparada en
 * texto plano) por autenticación real contra usuarios con hash, con:
 *   - límite de intentos por correo + IP, y bloqueo temporal (AUTH-03)
 *   - regeneración de sesión al entrar, contra fijación de sesión (AUTH-04)
 *   - rechazo de cuentas desactivadas (AUTH-08)
 */
class LoginForm extends Component
{
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    public function login()
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        $credentials = ['email' => mb_strtolower(trim($this->email)), 'password' => $this->password];

        if (! Auth::attempt($credentials, $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            // Mensaje genérico a propósito: no revela si el correo existe.
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // Cuenta desactivada: no entra aunque la contraseña sea correcta.
        if (! Auth::user()->isActive()) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => __('auth.account_disabled'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        session()->regenerate();

        $user = Auth::user();
        $user->forceFill([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip(),
        ])->save();

        // Respeta el idioma que el usuario tenga guardado en su cuenta.
        if ($user->locale) {
            session(['locale' => $user->locale]);
        }

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Bloqueo tras varios intentos fallidos. La clave combina correo e IP,
     * así que ni martillar un correo ni rotar correos desde una IP evade
     * el límite.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    protected function throttleKey(): string
    {
        return Str::transliterate(mb_strtolower($this->email) . '|' . request()->ip());
    }

    public function render()
    {
        return view('livewire.auth.login-form')
            ->layout('components.layouts.guest', ['title' => __('auth.sign_in')]);
    }
}
