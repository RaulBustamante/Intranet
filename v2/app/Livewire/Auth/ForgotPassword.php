<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Validate;
use Livewire\Component;

/**
 * Solicitud de recuperación de contraseña (AUTH-02).
 *
 * Usa el broker nativo de Laravel (tabla password_reset_tokens, ya migrada).
 * Responde siempre lo mismo, exista o no el correo: no filtra qué cuentas hay.
 */
class ForgotPassword extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    public bool $sent = false;

    public function send(): void
    {
        $this->validate();

        Password::sendResetLink(['email' => mb_strtolower(trim($this->email))]);

        // Mismo mensaje pase lo que pase (AUTH-02): no revela si existe.
        $this->sent = true;
    }

    public function render()
    {
        return view('livewire.auth.forgot-password')
            ->layout('components.layouts.guest', ['title' => __('auth.reset.request_title')]);
    }
}
