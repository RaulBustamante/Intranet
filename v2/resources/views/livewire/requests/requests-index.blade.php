<div>
    <x-page-header :title="__('nav.requests')" :subtitle="__('requests.subtitle')" />

    <x-guest-notice action="request" />

    {{-- Pestañas: mis solicitudes / bandeja del aprobador --}}
    <div class="mb-4 flex gap-1 border-b" style="border-color: var(--line);">
        <button wire:click="$set('tab', 'mine')" class="ah-nav-link pb-2.5"
                @if ($tab === 'mine') aria-current="page" @endif>{{ __('requests.mine') }}</button>
        @if ($this->inbox->isNotEmpty())
            <button wire:click="$set('tab', 'inbox')" class="ah-nav-link pb-2.5 inline-flex items-center gap-1.5"
                    @if ($tab === 'inbox') aria-current="page" @endif>
                {{ __('requests.inbox') }}
                <span class="ah-badge ah-badge-brand">{{ $this->inbox->count() }}</span>
            </button>
        @endif
    </div>

    @if ($tab === 'mine')
        {{-- Botones de nueva solicitud por tipo --}}
        <div class="mb-4 grid grid-cols-2 gap-2 sm:grid-cols-4">
            @foreach ($this->types as $t)
                <button wire:click="startNew({{ $t->id }})"
                        class="ah-card ah-card-hover flex items-center gap-2 p-3 text-left">
                    <x-icon :name="$t->icon" class="w-4 h-4 shrink-0 text-ink-50" />
                    <span class="truncate text-sm font-medium text-ink">{{ $t->name }}</span>
                </button>
            @endforeach
        </div>

        {{-- Lista de mis solicitudes con barra de pasos (REQ-03) --}}
        <div class="space-y-3">
            @forelse ($this->myRequests as $r)
                <x-card>
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-ink">#{{ $r->id }} · {{ $r->type->name }}</span>
                                @php
                                    $badge = match ($r->status) {
                                        'approved', 'completed' => 'ah-badge-ok',
                                        'rejected' => 'ah-badge-danger',
                                        'cancelled' => '',
                                        default => $r->isOverdue() ? 'ah-badge-warn' : 'ah-badge-brand',
                                    };
                                @endphp
                                <span class="ah-badge {{ $badge }}">
                                    {{ __('requests.status.' . $r->status) }}
                                    @if ($r->isOverdue()) · {{ __('requests.overdue') }} @endif
                                </span>
                            </div>
                            {{-- barra de pasos --}}
                            <div class="mt-2 flex items-center gap-1">
                                @foreach ($r->approvals as $a)
                                    <span class="h-1.5 w-8 rounded-full"
                                          style="background: {{ $a->decision === 'approved' ? 'var(--ok)' : ($a->decision === 'rejected' ? 'var(--danger)' : 'var(--paper-3)') }}"></span>
                                @endforeach
                                <span class="ml-2 text-xs text-ink-50">
                                    {{ $r->submitted_at?->locale(app()->getLocale())->diffForHumans() }}
                                    @if ($r->due_at && $r->isOpen()) · {{ __('requests.due') }} {{ $r->due_at->locale(app()->getLocale())->diffForHumans() }} @endif
                                </span>
                            </div>
                            {{-- comentario de rechazo, si lo hay --}}
                            @php $rej = $r->approvals->firstWhere('decision', 'rejected'); @endphp
                            @if ($rej && $rej->comment)
                                <p class="mt-2 rounded-md px-3 py-2 text-sm" style="background: color-mix(in srgb, var(--danger) 8%, transparent); color: var(--danger)">
                                    "{{ $rej->comment }}"
                                </p>
                            @endif
                        </div>
                        @if ($r->isOpen())
                            <x-button variant="ghost" wire:click="cancel({{ $r->id }})" wire:confirm="?">
                                {{ __('requests.cancel') }}
                            </x-button>
                        @endif
                    </div>
                </x-card>
            @empty
                <x-card><x-empty-state icon="inbox" :title="__('requests.none')" :body="false" /></x-card>
            @endforelse
        </div>
    @else
        {{-- Bandeja del aprobador --}}
        <div class="space-y-3">
            @forelse ($this->inbox as $r)
                <x-card>
                    <div class="mb-2 flex items-center gap-2">
                        <span class="font-medium text-ink">#{{ $r->id }} · {{ $r->type->name }}</span>
                        <span class="text-sm text-ink-50">· {{ $r->requester->display_name }}</span>
                        @if ($r->isOverdue()) <span class="ah-badge ah-badge-warn">{{ __('requests.overdue') }}</span> @endif
                    </div>
                    {{-- payload --}}
                    <dl class="mb-3 grid gap-1 text-sm sm:grid-cols-2">
                        @foreach ($r->type->field_schema as $f)
                            <div class="flex gap-2">
                                <dt class="text-ink-50">{{ $r->type->fieldLabel($f) }}:</dt>
                                <dd class="text-ink-70">{{ $r->payload[$f['key']] ?? '—' }}</dd>
                            </div>
                        @endforeach
                    </dl>
                    <div class="flex items-end gap-2">
                        <div class="flex-1">
                            <input type="text" wire:model="decisionComment" class="ah-input"
                                   placeholder="{{ __('requests.comment_placeholder') }}">
                            @error('decision') <p class="mt-1 text-xs" style="color: var(--danger)">{{ $message }}</p> @enderror
                        </div>
                        <x-button variant="secondary" wire:click="decide({{ $r->id }}, 'reject')">{{ __('requests.reject') }}</x-button>
                        <x-button variant="primary" wire:click="decide({{ $r->id }}, 'approve')">{{ __('requests.approve') }}</x-button>
                    </div>
                </x-card>
            @empty
                <x-card><x-empty-state icon="inbox" :title="__('requests.inbox_empty')" :body="false" /></x-card>
            @endforelse
        </div>
    @endif

    {{-- Modal de nueva solicitud, formulario generado desde el schema --}}
    @if ($typeId)
        @php $type = $this->types->firstWhere('id', $typeId); @endphp
        <div class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto px-4 py-[6vh]" role="dialog" aria-modal="true">
            <div class="absolute inset-0" wire:click="$set('typeId', null)"
                 style="background: color-mix(in srgb, var(--ink-900) 40%, transparent);"></div>
            <div class="ah-card relative w-full max-w-lg p-5 shadow-2xl">
                <h2 class="mb-4 text-lg">{{ $type->name }}</h2>
                <form wire:submit="submit" class="space-y-4">
                    @foreach ($type->field_schema as $f)
                        <div>
                            <label class="mb-1 block text-xs font-medium text-ink-70">
                                {{ $type->fieldLabel($f) }}
                                @if ($f['required'] ?? false) <span style="color: var(--brand-text)">*</span> @endif
                            </label>
                            @if (($f['type'] ?? 'text') === 'textarea')
                                <textarea wire:model="form.{{ $f['key'] }}" rows="3" class="ah-input"></textarea>
                            @elseif ($f['type'] === 'select')
                                <select wire:model="form.{{ $f['key'] }}" class="ah-input">
                                    <option value="">—</option>
                                    @foreach ($f['options'] as $opt)
                                        <option value="{{ $opt['es'] }}">{{ $opt[app()->getLocale()] ?? $opt['es'] }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input type="{{ $f['type'] }}" wire:model="form.{{ $f['key'] }}" class="ah-input">
                            @endif
                            @error("form.{$f['key']}") <p class="mt-1 text-xs" style="color: var(--danger)">{{ __('requests.field_required') }}</p> @enderror
                        </div>
                    @endforeach
                    <div class="flex justify-end gap-2 pt-1">
                        <x-button variant="secondary" type="button" wire:click="$set('typeId', null)">{{ __('admin.actions.cancel') }}</x-button>
                        <x-button variant="primary" type="submit">{{ __('requests.send') }}</x-button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <div x-data="{ show: false, msg: '' }" x-on:notify.window="msg = $event.detail.message; show = true; setTimeout(() => show = false, 4000)"
         x-show="show" x-cloak class="ah-card fixed bottom-5 left-1/2 z-50 -translate-x-1/2 px-4 py-2.5 shadow-lg">
        <p class="text-sm text-ink" x-text="msg"></p>
    </div>
</div>
