<div>
    @if ($this->poll)
        @php $total = array_sum($this->tally); @endphp
        <x-card :eyebrow="__('polls.eyebrow')">
            <p class="mb-3 font-medium text-ink">{{ $this->poll->question }}</p>

            @if ($this->hasVoted)
                {{-- Resultados --}}
                <div class="space-y-2">
                    @foreach ($this->poll->options as $opt)
                        @php
                            $n = $this->tally[$opt->id] ?? 0;
                            $pct = $total > 0 ? round($n / $total * 100) : 0;
                        @endphp
                        <div>
                            <div class="mb-0.5 flex justify-between text-xs">
                                <span class="text-ink-70">{{ $opt->label }}</span>
                                <span class="text-ink-50">{{ $pct }}%</span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full" style="background: var(--paper-3);">
                                <div class="h-full rounded-full" style="width: {{ $pct }}%; background: var(--brand);"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <p class="mt-2 text-xs text-ink-50">{{ __('polls.votes', ['n' => $total]) }}</p>
            @else
                {{-- Votar --}}
                <form wire:submit="vote" class="space-y-2">
                    @foreach ($this->poll->options as $opt)
                        <label class="flex cursor-pointer items-center gap-2.5 rounded-md border px-3 py-2 text-sm hover:bg-paper-2"
                               style="border-color: var(--line);">
                            <input type="radio" wire:model="optionId" value="{{ $opt->id }}">
                            <span class="text-ink">{{ $opt->label }}</span>
                        </label>
                    @endforeach
                    <x-button variant="primary" type="submit" class="w-full">{{ __('polls.vote') }}</x-button>
                </form>
            @endif
        </x-card>
    @endif
</div>
