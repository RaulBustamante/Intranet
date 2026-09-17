<?php

namespace App\Livewire\Auth;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;

/**
 * Fijar una contraseña nueva desde el enlace del correo (AUTH-02).
 *
 * El token es de un solo uso: el broker de Laravel lo borra al consumirlo,
 * así que reutilizar el enlace falla (verificado en las pruebas).
 */
class ResetPassword extends Component
{
    public string $token = '';

    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required|string|min:8|confirmed')]
    public string $password = '';

    public string $password_confirmation = '';

    public bool $done = false;

    public function mount(string $token): void
    {
        $this->token = $token;
        $this->email = request()->string('email')->toString();
    }

    public function save(): void
    {
        $this->validate();

        $status = Password::reset(
            [
                'email' => mb_strtolower(trim($this->email)),
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ],
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                    'must_change_password' => false,
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PasswordReset) {
            $this->done = true;

            return;
        }

        $this->addError('email', __($status));
    }

    public function render()
    {
        return view('livewire.auth.reset-password')
            ->layout('components.layouts.guest', ['title' => __('auth.reset.title')]);
    }
}
