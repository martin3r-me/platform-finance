<?php

namespace Platform\Finance\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    public function getTeamId(): ?int
    {
        $user = auth()->user();
        return $user && $user->currentTeam ? $user->currentTeam->id : null;
    }

    public function getTotalAccountsProperty(): int
    {
        $teamId = $this->getTeamId();
        if (!$teamId) {
            return 0;
        }
        // TODO: Implementiere Finance-Accounts Model
        return 0;
    }

    public function getActiveAccountsProperty(): int
    {
        $teamId = $this->getTeamId();
        if (!$teamId) {
            return 0;
        }
        // TODO: Implementiere Finance-Accounts Model
        return 0;
    }

    public function getTotalTransactionsProperty(): int
    {
        $teamId = $this->getTeamId();
        if (!$teamId) {
            return 0;
        }
        // TODO: Implementiere Finance-Transactions Model
        return 0;
    }

    public function getRecentTransactionsProperty()
    {
        $teamId = $this->getTeamId();
        if (!$teamId) {
            return collect();
        }
        // TODO: Implementiere Finance-Transactions Model
        return collect();
    }

    public function render()
    {
        return view('finance::livewire.dashboard')
            ->layout('platform::layouts.app');
    }
}