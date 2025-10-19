<?php

namespace Platform\Finance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Platform\Core\Models\Team;

class FinanceAccountType extends Model
{
    protected $table = 'finance_account_types';
    
    protected $fillable = [
        'team_id',
        'code',
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Team relationship
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Accounts relationship
     */
    public function accounts(): HasMany
    {
        return $this->hasMany(FinanceAccount::class, 'account_type_id');
    }

    /**
     * Scope for active account types
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
     * Get active account types ordered by name
     */
    public static function getActiveOrdered()
    {
        return static::active()
            ->orderBy('name')
            ->get();
    }
}
