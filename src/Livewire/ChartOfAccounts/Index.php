<?php

namespace Platform\Finance\Livewire\ChartOfAccounts;

use Livewire\Component;
use Livewire\WithPagination;
use Platform\Finance\Models\FinanceAccount;
use Platform\Finance\Models\FinanceAccountType;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $accountTypeId = null;
    public bool $onlyActive = true;

    protected $queryString = [
        'search' => ['except' => ''],
        'accountTypeId' => ['except' => null],
        'onlyActive' => ['except' => true],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingAccountTypeId(): void
    {
        $this->resetPage();
    }

    public function updatingOnlyActive(): void
    {
        $this->resetPage();
    }

    protected function getTeamId(): ?int
    {
        $user = auth()->user();
        return $user && $user->currentTeam ? $user->currentTeam->id : null;
    }

    public function getAccountTypesProperty()
    {
        $teamId = $this->getTeamId();
        if (!$teamId) {
            return collect();
        }
        return FinanceAccountType::forTeam($teamId)->active()->orderBy('name')->get();
    }

    public function render()
    {
        $teamId = $this->getTeamId();

        $query = FinanceAccount::query();
        if ($teamId) {
            $query->forTeam($teamId);
        }

        if ($this->onlyActive) {
            $query->active()->valid();
        }

        if ($this->accountTypeId) {
            $query->where('account_type_id', $this->accountTypeId);
        }

        if ($this->search !== '') {
            $like = '%' . str_replace(' ', '%', $this->search) . '%';
            $query->where(function ($q) use ($like) {
                $q->where('number', 'like', $like)
                  ->orWhere('number_from', 'like', $like)
                  ->orWhere('number_to', 'like', $like)
                  ->orWhere('name', 'like', $like)
                  ->orWhere('category', 'like', $like)
                  ->orWhere('function', 'like', $like)
                  ->orWhere('purpose', 'like', $like);
            });
        }

        $accounts = $query
            ->with('accountType')
            ->orderBy('number')
            ->paginate(20);

        return view('finance::livewire.chart-of-accounts.index', [
            'accounts' => $accounts,
        ])->layout('platform::layouts.app');
    }
}
