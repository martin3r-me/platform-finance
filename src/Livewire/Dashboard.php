<?php

namespace Platform\Finance\Livewire;

use Livewire\Component;
use Platform\Finance\Models\FinanceAccount;
use Platform\Finance\Models\FinanceAccountType;

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
        return (int) FinanceAccount::forTeam($teamId)->count();
    }

    public function getActiveAccountsProperty(): int
    {
        $teamId = $this->getTeamId();
        if (!$teamId) {
            return 0;
        }
        return (int) FinanceAccount::forTeam($teamId)->active()->valid()->count();
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

    public function getAccountsByTypeProperty()
    {
        $teamId = $this->getTeamId();
        if (!$teamId) {
            return collect();
        }

        $types = FinanceAccountType::forTeam($teamId)->active()->get();
        $counts = FinanceAccount::forTeam($teamId)
            ->active()
            ->valid()
            ->selectRaw('account_type_id, COUNT(*) as aggregate_count')
            ->groupBy('account_type_id')
            ->pluck('aggregate_count', 'account_type_id');

        return $types->map(function ($type) use ($counts) {
            return (object) [
                'id' => $type->id,
                'name' => $type->name,
                'code' => $type->code,
                'count' => (int) ($counts[$type->id] ?? 0),
            ];
        });
    }

    public function getRecentAccountsProperty()
    {
        $teamId = $this->getTeamId();
        if (!$teamId) {
            return collect();
        }
        return FinanceAccount::forTeam($teamId)
            ->active()
            ->valid()
            ->with('accountType')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    public function render()
    {
        return view('finance::livewire.dashboard')
            ->layout('platform::layouts.app');
    }
}