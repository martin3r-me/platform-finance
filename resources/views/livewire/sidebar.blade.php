{{-- resources/views/vendor/finance/livewire/sidebar-content.blade.php --}}
<div>
    {{-- Modul Header --}}
    <x-sidebar-module-header module-name="Finance" />
    
    {{-- Abschnitt: Allgemein --}}
    <div>
        <h4 x-show="!collapsed" class="px-4 py-3 text-xs tracking-wide font-semibold text-[color:var(--ui-muted)] uppercase">Allgemein</h4>

        {{-- Dashboard --}}
        <a href="{{ route('finance.dashboard') }}"
           class="relative flex items-center px-3 py-2 my-1 rounded-md font-medium transition"
           :class="[
               window.location.pathname === '/' || 
               window.location.pathname.endsWith('/finance') || 
               window.location.pathname.endsWith('/finance/') ||
               (window.location.pathname.split('/').length === 1 && window.location.pathname === '/')
                   ? 'bg-[color:var(--ui-primary)] text-[color:var(--ui-on-primary)] shadow'
                   : 'text-[color:var(--ui-secondary)] hover:bg-[color:var(--ui-primary-5)] hover:text-[color:var(--ui-primary)]',
               collapsed ? 'justify-center' : 'gap-3'
           ]"
           wire:navigate>
            <x-heroicon-o-chart-bar class="w-6 h-6 flex-shrink-0"/>
            <span x-show="!collapsed" class="truncate">Dashboard</span>
        </a>

        {{-- Kontenplan --}}
        <a href="{{ route('finance.chart-of-accounts.index') }}"
           class="relative flex items-center px-3 py-2 my-1 rounded-md font-medium transition"
           :class="[
               window.location.pathname.includes('/chart-of-accounts') || 
               window.location.pathname.endsWith('/chart-of-accounts') ||
               window.location.pathname.endsWith('/chart-of-accounts/')
                   ? 'bg-[color:var(--ui-primary)] text-[color:var(--ui-on-primary)] shadow'
                   : 'text-[color:var(--ui-secondary)] hover:bg-[color:var(--ui-primary-5)] hover:text-[color:var(--ui-primary)]',
               collapsed ? 'justify-center' : 'gap-3'
           ]"
           wire:navigate>
            <x-heroicon-o-banknotes class="w-6 h-6 flex-shrink-0"/>
            <span x-show="!collapsed" class="truncate">Kontenplan</span>
        </a>

        {{-- Kontenarten --}}
        <a href="#"
           class="relative flex items-center px-3 py-2 my-1 rounded-md font-medium transition"
           :class="[
               window.location.pathname.includes('/account-types') || 
               window.location.pathname.endsWith('/account-types') ||
               window.location.pathname.endsWith('/account-types/')
                   ? 'bg-[color:var(--ui-primary)] text-[color:var(--ui-on-primary)] shadow'
                   : 'text-[color:var(--ui-secondary)] hover:bg-[color:var(--ui-primary-5)] hover:text-[color:var(--ui-primary)]',
               collapsed ? 'justify-center' : 'gap-3'
           ]"
           wire:navigate>
            <x-heroicon-o-squares-2x2 class="w-6 h-6 flex-shrink-0"/>
            <span x-show="!collapsed" class="truncate">Kontenarten</span>
        </a>
    </div>

    {{-- Abschnitt: Schnellzugriff --}}
    <div x-show="!collapsed">
        <h4 class="px-4 py-3 text-xs tracking-wide font-semibold text-[color:var(--ui-muted)] uppercase">Schnellzugriff</h4>

        {{-- Neueste Konten --}}
        @foreach($recentAccounts ?? [] as $account)
            <a href="#"
               class="relative flex items-center px-3 py-2 my-1 rounded-md font-medium transition gap-3"
               :class="[
                   'text-[color:var(--ui-secondary)] hover:bg-[color:var(--ui-primary-5)] hover:text-[color:var(--ui-primary)]'
               ]"
               wire:navigate>
                <x-heroicon-o-banknotes class="w-6 h-6 flex-shrink-0"/>
                <span class="truncate">{{ $account->name ?? 'Konto' }}</span>
            </a>
        @endforeach
    </div>
</div>