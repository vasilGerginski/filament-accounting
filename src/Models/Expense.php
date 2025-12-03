<?php

namespace Noblehouse\FilamentAccounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    protected $fillable = [
        'expense_type_id',
        'type',
        'price',
        'amortization_percentage',
        'date',
        'description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'amortization_percentage' => 'decimal:2',
        'date' => 'date',
    ];

    public function expenseType(): BelongsTo
    {
        return $this->belongsTo(ExpenseType::class);
    }
}