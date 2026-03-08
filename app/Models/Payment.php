<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Payment extends Model
{
    protected $fillable = [
        'empresa_id',
        'subscription_id',
        'amount',
        'currency',
        'method',
        'status',
        'gateway_payment_id',
        'gateway',
        'gateway_data',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_data' => 'array',
        'paid_at' => 'datetime',
    ];

    // Relationships

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    // Status helpers

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isRefunded(): bool
    {
        return $this->status === 'refunded';
    }

    // Accessors

    public function getAmountFormatadoAttribute(): string
    {
        return 'R$ ' . number_format($this->amount, 2, ',', '.');
    }
}
