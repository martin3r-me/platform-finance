<?php

namespace Platform\Finance\Livewire;

use Livewire\Component;

class Sidebar extends Component
{
    public function getRecentTransactionsProperty()
    {
        // TODO: Implementiere Finance-Transactions Model
        return collect();
    }

    public function render()
    {
        return view('finance::livewire.sidebar')
            ->layout('platform::layouts.app');
    }
}