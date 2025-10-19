<?php

namespace Platform\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Platform\Core\Models\Team;

class FinanceAccount extends Model
{
    use SoftDeletes;
    
    protected $table = 'finance_accounts';
    
    protected $fillable = [
        'team_id',
        'account_type_id',
        'number',
        'number_from',
        'number_to',
        'name',
        'category',
        'function',
        'purpose',
        'external_ref',
        'is_active',
        'valid_from',
        'valid_to',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'valid_from' => 'date',
        'valid_to' => 'date',
    ];

    /**
     * Team relationship
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Account type relationship
     */
    public function accountType(): BelongsTo
    {
        return $this->belongsTo(FinanceAccountType::class, 'account_type_id');
    }

    /**
     * Scope for active accounts
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for team
     */
    public function scopeForTeam($query, $teamId)
    {
        return $query->where('team_id', $teamId);
    }

    /**
     * Scope for valid accounts (within date range)
     */
    public function scopeValid($query, $date = null)
    {
        $date = $date ?? now();
        return $query->where(function ($q) use ($date) {
            $q->whereNull('valid_from')
              ->orWhere('valid_from', '<=', $date);
        })->where(function ($q) use ($date) {
            $q->whereNull('valid_to')
              ->orWhere('valid_to', '>=', $date);
        });
    }

    /**
     * Get active accounts for team ordered by number
     */
    public static function getActiveForTeam($teamId)
    {
        return static::forTeam($teamId)
            ->active()
            ->valid()
            ->with('accountType')
            ->orderBy('number')
            ->get();
    }
}
