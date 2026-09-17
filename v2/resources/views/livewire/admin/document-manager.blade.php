<div>
    <x-page-header :title="__('docadmin.title')" :subtitle="__('docadmin.subtitle')">
        <x-slot:actions>
            <x-button variant="secondary" href="{{ route('documents') }}" icon="file">{{ __('nav.documents') }}</x-button>
            <x-button variant="primary" wire:click="openCreate" icon="plus">{{ __('docadmin.new') }}</x-button>
        </x-slot:actions>
    </x-page-header>

    {{-- Alta de un documento nuevo --}}
    @if ($showForm)
        <x-card class="mb-5">
            <form wire:submit="save" class="space-y-4">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm text-ink-70">{{ __('docadmin.title_es') }}</label>
                        <input type="text" wire:model="titleEs" class="ah-input w-full">
                        @error('titleEs') <p class="mt-1 text-xs" style="color: var(--danger)">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-sm text-ink-70">{{ __('docadmin.title_en') }}</label>
                        <input type="text" wire:model="titleEn" class="ah-input w-full">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm text-ink-70">{{ __('docadmin.category') }}</label>
                        <select wire:model="categoryId" class="ah-input w-full">
                            <option value="">{{ __('docadmin.no_category') }}</option>
                            @foreach ($this->categories as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <label class="inline-flex items-center gap-2 text-sm text-ink-70">
                            <input type="checkbox" wire:model="isPrivate" class="rounded">
                            {{ __('docadmin.private') }}
                        </label>
                    </div>
                </div>
                <div>
                    <label class="mb-1 block text-sm text-ink-70">{{ __('docadmin.file') }}</label>
                    <input type="file" wire:model="file" class="ah-input w-full">
                    <div wire:loading wire:target="file" class="mt-1 text-xs text-ink-50">{{ __('app.states.loading') }}</div>
                    @error('file') <p class="mt-1 text-xs" style="color: var(--danger)">{{ $message }}</p> @enderror
                </div>
                <div class="flex justify-end gap-2">
                    <x-button variant="secondary" type="button" wire:click="$set('showForm', false)">{{ __('docadmin.cancel') }}</x-button>
                    <x-button variant="primary" type="submit">{{ __('docadmin.save') }}</x-button>
                </div>
            </form>
        </x-card>
    @endif

    {{-- Lista de documentos --}}
    @forelse ($this->documents as $doc)
        <x-card class="mb-3">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="min-w-0">
                    <p class="truncate font-medium text-ink">{{ $doc->title }}</p>
                    <p class="text-xs text-ink-50">
                        {{ $doc->category?->name ?? __('docadmin.no_category') }}
                        · v{{ $doc->version_no }}
                        · {{ $doc->versions_count }} {{ __('docadmin.versions') }}
                        @if ($doc->is_private) · <span style="color: var(--brand-text)">{{ __('docadmin.private') }}</span> @endif
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-1.5">
                    <a href="{{ $doc->url() }}" target="_blank" rel="noopener" class="ah-btn ah-btn-ghost">
                        <x-icon name="file" class="w-4 h-4" /> <span class="hidden sm:inline">{{ __('docadmin.download') }}</span>
                    </a>
                    <button wire:click="startVersion({{ $doc->id }})" class="ah-btn ah-btn-ghost">
                        <x-icon name="plus" class="w-4 h-4" /> <span class="hidden sm:inline">{{ __('docadmin.new_version') }}</span>
                    </button>
                    <button wire:click="toggleVersions({{ $doc->id }})" class="ah-btn ah-btn-ghost">
                        <x-icon name="list" class="w-4 h-4" /> <span class="hidden sm:inline">{{ __('docadmin.history') }}</span>
                    </button>
                    <button wire:click="delete({{ $doc->id }})" wire:confirm="{{ __('docadmin.confirm_delete') }}" class="ah-btn ah-btn-ghost" style="color: var(--danger)">
                        <x-icon name="trash" class="w-4 h-4" />
                    </button>
                </div>
            </div>

            {{-- Formulario de nueva versión, en línea --}}
            @if ($versioningId === $doc->id)
                <form wire:submit="saveVersion" class="mt-4 border-t pt-4 space-y-3" style="border-color: var(--line);">
                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm text-ink-70">{{ __('docadmin.file') }}</label>
                            <input type="file" wire:model="versionFile" class="ah-input w-full">
                            @error('versionFile') <p class="mt-1 text-xs" style="color: var(--danger)">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-sm text-ink-70">{{ __('docadmin.note') }}</label>
                            <input type="text" wire:model="versionNote" class="ah-input w-full" placeholder="{{ __('docadmin.note_ph') }}">
                        </div>
                    </div>
                    <div wire:loading wire:target="versionFile" class="text-xs text-ink-50">{{ __('app.states.loading') }}</div>
                    <div class="flex justify-end gap-2">
                        <x-button variant="secondary" type="button" wire:click="$set('versioningId', null)">{{ __('docadmin.cancel') }}</x-button>
                        <x-button variant="primary" type="submit">{{ __('docadmin.upload_version') }}</x-button>
                    </div>
                </form>
            @endif

            {{-- Historial de versiones --}}
            @if ($expandedId === $doc->id)
                <div class="mt-4 border-t pt-3" style="border-color: var(--line);">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs text-ink-50">
                                <th class="pb-1.5">{{ __('docadmin.version') }}</th>
                                <th class="pb-1.5">{{ __('docadmin.uploaded') }}</th>
                                <th class="pb-1.5">{{ __('docadmin.note') }}</th>
                                <th class="pb-1.5"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($doc->versions as $v)
                                <tr class="border-t" style="border-color: var(--line);">
                                    <td class="py-1.5">v{{ $v->version_no }}</td>
                                    <td class="py-1.5 text-ink-70">{{ $v->created_at?->isoFormat('D MMM YYYY, HH:mm') }}</td>
                                    <td class="py-1.5 text-ink-70">{{ $v->note ?: '—' }}</td>
                                    <td class="py-1.5 text-right">
                                        <a href="{{ $v->url() }}" target="_blank" rel="noopener" class="ah-link">{{ __('docadmin.download') }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-card>
    @empty
        <x-empty-state :title="__('docadmin.none_title')" :body="__('docadmin.none_body')" />
    @endforelse

    <div class="mt-4">{{ $this->documents->links() }}</div>
</div>
