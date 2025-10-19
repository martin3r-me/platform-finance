<?php

namespace Platform\Finance\Livewire;

use Livewire\Component;

class Sidebar extends Component
{
    public function getRecentAccountsProperty()
    {
        $teamId = $this->getTeamId();
        if (!$teamId) {
            return collect();
        }
        return \Platform\Finance\Models\FinanceAccount::forTeam($teamId)
            ->active()
            ->valid()
            ->with('accountType')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
    }

    public function getTeamId(): ?int
    {
        $user = auth()->user();
        return $user && $user->currentTeam ? $user->currentTeam->id : null;
    }

    public function render()
    {
        return view('finance::livewire.sidebar')
            ->layout('platform::layouts.app');
    }
}