<x-ui-page>
    <x-slot name="navbar">
        <x-ui-page-navbar title="Finance Dashboard" />
    </x-slot>

    <x-slot name="sidebar">
        <x-ui-page-sidebar title="Schnellzugriff" width="w-80" :defaultOpen="true" side="left">
            <div class="p-6 space-y-6">
                <div>
                    <h3 class="text-sm font-bold text-[var(--ui-secondary)] uppercase tracking-wider mb-3">Aktionen</h3>
                    <div class="space-y-2">
                        <x-ui-button variant="secondary" size="sm" href="#" wire:navigate class="w-full">
                            <span class="flex items-center gap-2">
                                @svg('heroicon-o-banknotes','w-4 h-4')
                                Konten verwalten
                            </span>
                        </x-ui-button>
                        <x-ui-button variant="secondary" size="sm" href="#" wire:navigate class="w-full">
                            <span class="flex items-center gap-2">
                                @svg('heroicon-o-arrow-path','w-4 h-4')
                                Transaktion erstellen
                            </span>
                        </x-ui-button>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-[var(--ui-secondary)] uppercase tracking-wider mb-3">Statistiken</h3>
                    <div class="space-y-3">
                        <div class="py-3 px-4 bg-[var(--ui-muted-5)] rounded-lg border border-[var(--ui-border)]/40 flex items-center justify-between">
                            <span class="text-xs text-[var(--ui-muted)]">Gesamt Konten</span>
                            <span class="text-lg font-bold text-[var(--ui-secondary)]">{{ $this->totalAccounts }}</span>
                        </div>
                        <div class="py-3 px-4 bg-[var(--ui-muted-5)] rounded-lg border border-[var(--ui-border)]/40 flex items-center justify-between">
                            <span class="text-xs text-[var(--ui-muted)]">Aktive Konten</span>
                            <span class="text-lg font-bold text-[var(--ui-secondary)]">{{ $this->activeAccounts }}</span>
                        </div>
                        <div class="py-3 px-4 bg-[var(--ui-muted-5)] rounded-lg border border-[var(--ui-border)]/40 flex items-center justify-between">
                            <span class="text-xs text-[var(--ui-muted)]">Transaktionen</span>
                            <span class="text-lg font-bold text-[var(--ui-secondary)]">{{ $this->totalTransactions }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </x-ui-page-sidebar>
    </x-slot>

    <x-slot name="activity">
        <x-ui-page-sidebar title="Aktivitäten" width="w-80" :defaultOpen="false" storeKey="activityOpen" side="right">
            <div class="p-6 text-sm text-[var(--ui-muted)]">Keine Aktivitäten verfügbar</div>
        </x-ui-page-sidebar>
    </x-slot>

    <x-ui-page-container>
        <!-- Haupt-Statistiken -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
            <x-ui-dashboard-tile title="Alle Konten" :count="$this->totalAccounts" icon="banknotes" variant="primary" size="lg" href="#" />
            <x-ui-dashboard-tile title="Aktive Konten" :count="$this->activeAccounts" icon="check-circle" variant="success" size="lg" />
        </div>

        <!-- Verteilung nach Typen + Neueste Transaktionen -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <x-ui-panel title="Konten nach Typ" subtitle="Nach Kontenart gruppiert">
                <div class="space-y-2">
                    @php($byType = $this->accountsByType)
                    @forelse(($byType ?? collect())->take(5) as $row)
                        <div class="group flex items-center justify-between p-3 rounded-lg border border-[var(--ui-border)]/60 bg-[var(--ui-surface)] hover:border-[var(--ui-primary)]/60 hover:bg-[var(--ui-primary-5)] transition-colors">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-lg bg-[var(--ui-muted-5)] border border-[var(--ui-border)]/60 flex items-center justify-center text-xs font-semibold text-[var(--ui-secondary)]">
                                    @svg('heroicon-o-banknotes','w-4 h-4')
                                </div>
                                <div class="min-w-0">
                                    <div class="font-medium text-[var(--ui-secondary)] truncate">{{ $row->name }}</div>
                                    <div class="text-xs text-[var(--ui-muted)]">{{ $row->code }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-[var(--ui-secondary)]">{{ $row->count }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-sm text-[var(--ui-muted)] p-4 text-center">Keine Konten vorhanden.</div>
                    @endforelse
                </div>
            </x-ui-panel>

            <x-ui-panel title="Neueste Konten" subtitle="Top 5">
                <div class="space-y-2">
                    @php($recent = $this->recentAccounts)
                    @forelse(($recent ?? collect())->take(5) as $account)
                        <div class="group flex items-center justify-between p-3 rounded-lg border border-[var(--ui-border)]/60 bg-[var(--ui-surface)] hover:border-[var(--ui-primary)]/60 hover:bg-[var(--ui-primary-5)] transition-colors">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-2 h-2 rounded-full {{ $account->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></div>
                                <div class="min-w-0">
                                    <div class="font-medium text-[var(--ui-secondary)] truncate">{{ $account->name }}</div>
                                    <div class="text-xs text-[var(--ui-muted)]">
                                        {{ $account->number }}
                                        @if($account->accountType)
                                            • {{ $account->accountType->name }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <x-ui-badge variant="secondary" size="sm">{{ $account->created_at?->format('d.m.Y') }}</x-ui-badge>
                        </div>
                    @empty
                        <div class="text-sm text-[var(--ui-muted)] p-4 text-center">Keine Konten vorhanden.</div>
                    @endforelse
                </div>
            </x-ui-panel>
        </div>
    </x-ui-page-container>
</x-ui-page>
</x-ui-page>