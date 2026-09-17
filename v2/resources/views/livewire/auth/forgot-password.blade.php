<div class="ah-card p-6">
    @if ($sent)
        <div class="text-center">
            <div class="mx-auto mb-3 flex h-10 w-10 items-center justify-center rounded-full"
                 style="background: var(--brand-tint); color: var(--brand-text);">
                <x-icon name="inbox" class="w-5 h-5" />
            </div>
            <p class="text-sm text-ink-70">{{ __('auth.reset.sent') }}</p>
            <a href="{{ route('login') }}" class="ah-link mt-4 inline-block text-sm">{{ __('auth.reset.back') }}</a>
        </div>
    @else
        <h2 class="mb-1 text-base">{{ __('auth.reset.request_title') }}</h2>
        <p class="mb-4 text-sm text-ink-50">{{ __('auth.reset.request_help') }}</p>

        <form wire:submit="send" class="space-y-4">
            <div>
                <label for="email" class="mb-1 block text-xs font-medium text-ink-70">{{ __('auth.email') }}</label>
                <input id="email" type="email" wire:model="email" autofocus required class="ah-input">
                @error('email') <p class="mt-1 text-xs" style="color: var(--danger)">{{ $message }}</p> @enderror
            </div>
            <x-button type="submit" variant="primary" class="w-full">{{ __('auth.reset.send') }}</x-button>
            <div class="text-center">
                <a href="{{ route('login') }}" class="ah-link text-sm">{{ __('auth.reset.back') }}</a>
            </div>
        </form>
    @endif
</div>
