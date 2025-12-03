<?php

namespace VasilGerginski\FilamentAccounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Income extends Model
{
    protected $fillable = [
        'income_type_id',
        'user_id',
        'amount',
        'date',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    public function incomeType(): BelongsTo
    {
        return $this->belongsTo(IncomeType::class);
    }

    public function user(): BelongsTo
    {
        $userModel = config('filament-accounting.user_model', 'App\\Models\\User');

        return $this->belongsTo($userModel);
    }
}