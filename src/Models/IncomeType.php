<?php

namespace Noblehouse\FilamentAccounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IncomeType extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function incomes(): HasMany
    {
        return $this->hasMany(Income::class);
    }
}