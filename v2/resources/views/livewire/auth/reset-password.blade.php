<div class="ah-card p-6">
    @if ($done)
        <div class="text-center">
            <p class="text-sm text-ink-70">{{ __('auth.reset.done') }}</p>
            <a href="{{ route('login') }}" class="ah-btn ah-btn-primary mt-4">{{ __('auth.sign_in') }}</a>
        </div>
    @else
        <h2 class="mb-4 text-base">{{ __('auth.reset.title') }}</h2>
        <form wire:submit="save" class="space-y-4">
            <div>
                <label for="email" class="mb-1 block text-xs font-medium text-ink-70">{{ __('auth.email') }}</label>
                <input id="email" type="email" wire:model="email" required class="ah-input">
                @error('email') <p class="mt-1 text-xs" style="color: var(--danger)">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="password" class="mb-1 block text-xs font-medium text-ink-70">{{ __('auth.reset.new') }}</label>
                <input id="password" type="password" wire:model="password" autocomplete="new-password" required class="ah-input">
                @error('password') <p class="mt-1 text-xs" style="color: var(--danger)">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="password_confirmation" class="mb-1 block text-xs font-medium text-ink-70">{{ __('auth.reset.confirm') }}</label>
                <input id="password_confirmation" type="password" wire:model="password_confirmation" autocomplete="new-password" required class="ah-input">
            </div>
            <x-button type="submit" variant="primary" class="w-full">{{ __('auth.reset.save') }}</x-button>
        </form>
    @endif
</div>
