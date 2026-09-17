<div class="ah-card p-6">
    <form wire:submit="login" class="space-y-4">

        <div>
            <label for="email" class="mb-1 block text-xs font-medium text-ink-70">
                {{ __('auth.email') }}
            </label>
            <input id="email" type="email" wire:model="email" autocomplete="username"
                   autofocus required class="ah-input"
                   @error('email') style="border-color: var(--danger)" @enderror>
        </div>

        <div>
            <label for="password" class="mb-1 block text-xs font-medium text-ink-70">
                {{ __('auth.password_label') }}
            </label>
            <input id="password" type="password" wire:model="password" autocomplete="current-password"
                   required class="ah-input">
        </div>

        {{-- Un solo lugar para el error, con mensaje genérico: no revela si el
             correo existe ni si fue la contraseña la que falló (AUTH-01). --}}
        @error('email')
            <p class="text-xs" style="color: var(--danger)" role="alert">{{ $message }}</p>
        @enderror

        <label class="flex items-center gap-2 text-sm text-ink-70">
            <input type="checkbox" wire:model="remember" class="rounded border" style="border-color: var(--line)">
            {{ __('auth.remember') }}
        </label>

        <x-button type="submit" variant="primary" class="w-full">
            <span wire:loading.remove wire:target="login">{{ __('auth.sign_in') }}</span>
            <span wire:loading wire:target="login">{{ __('app.states.loading') }}</span>
        </x-button>

        <div class="pt-1 text-center">
            <a href="{{ route('password.request') }}" class="ah-link text-sm">
                {{ __('auth.forgot') }}
            </a>
        </div>
    </form>
</div>
