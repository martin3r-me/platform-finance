<x-ui-page>
    <x-slot name="navbar">
        <x-ui-page-navbar title="Kontenplan" />
    </x-slot>

    <x-slot name="sidebar">
        <x-ui-page-sidebar title="Filter" width="w-80" :defaultOpen="true" side="left">
            <div class="p-6 space-y-6">
                <div class="space-y-2">
                    <x-ui-input-text 
                        name="search" 
                        placeholder="Suchen (Nummer, Name, Kategorie)" 
                        class="w-full" 
                        size="sm" 
                    />

                    <x-ui-input-select 
                        name="accountTypeId" 
                        label="Kontenart" 
                        :options="$this->accountTypes" 
                        optionValue="id" 
                        optionLabel="name" 
                        :nullable="true" 
                        nullLabel="Alle Kontenarten" 
                        size="sm" 
                    />

                    <div class="flex items-center space-x-2">
                        <input type="checkbox" wire:model="onlyActive" id="onlyActive" class="rounded">
                        <label for="onlyActive" class="text-sm text-[var(--ui-secondary)]">Nur aktive Konten</label>
                    </div>
                </div>
            </div>
        </x-ui-page-sidebar>
    </x-slot>

    <x-ui-page-container>
        <x-ui-panel title="Konten" subtitle="{{ $accounts->total() }} Einträge gefunden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[var(--ui-border)]/60">
                    <thead class="bg-[var(--ui-muted-5)]">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-[var(--ui-muted)] uppercase tracking-wider">Nummer</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-[var(--ui-muted)] uppercase tracking-wider">Bereich</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-[var(--ui-muted)] uppercase tracking-wider">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-[var(--ui-muted)] uppercase tracking-wider">Art</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-[var(--ui-muted)] uppercase tracking-wider">Kategorie</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-[var(--ui-muted)] uppercase tracking-wider">Funktion</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-[var(--ui-muted)] uppercase tracking-wider">Gültig</th>
                        </tr>
                    </thead>
                    <tbody class="bg-[var(--ui-surface)] divide-y divide-[var(--ui-border)]/60">
                        @forelse($accounts as $account)
                            <tr class="hover:bg-[var(--ui-primary-5)]/50">
                                <td class="px-4 py-3 whitespace-nowrap font-mono text-[var(--ui-secondary)]">{{ $account->number }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-[var(--ui-secondary)]">{{ $account->number_from }}{{ $account->number_to ? ' - ' . $account->number_to : '' }}</td>
                                <td class="px-4 py-3 text-sm text-[var(--ui-secondary)]">{{ $account->name }}</td>
                                <td class="px-4 py-3 text-sm text-[var(--ui-secondary)]">{{ $account->accountType?->name }}</td>
                                <td class="px-4 py-3 text-sm text-[var(--ui-secondary)]">{{ $account->category ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-[var(--ui-secondary)]">{{ $account->function ?? '—' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-right text-xs text-[var(--ui-muted)]">
                                    @if($account->valid_from)
                                        {{ $account->valid_from?->format('d.m.Y') }}
                                    @else
                                        —
                                    @endif
                                    –
                                    @if($account->valid_to)
                                        {{ $account->valid_to?->format('d.m.Y') }}
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-sm text-[var(--ui-muted)]">
                                    Keine Konten gefunden.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $accounts->links() }}
            </div>
        </x-ui-panel>
    </x-ui-page-container>
</x-ui-page>
