<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Subscription extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'empresa_id',
        'plano_id',
        'status',
        'periodicidade',
        'data_inicio',
        'data_fim',
        'trial_termina_em',
        'proxima_cobranca',
        'gateway',
        'gateway_subscription_id',
        'gateway_customer_id',
        'valor_atual',
        'observacoes',
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
        'trial_termina_em' => 'date',
        'proxima_cobranca' => 'date',
        'valor_atual' => 'decimal:2',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'plano_id', 'periodicidade', 'valor_atual', 'proxima_cobranca'])
            ->logOnlyDirty();
    }

    // Relationships

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function plano(): BelongsTo
    {
        return $this->belongsTo(Plano::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    // Status helpers

    public function isAtiva(): bool
    {
        return $this->status === 'ativa';
    }

    public function isTrial(): bool
    {
        return $this->status === 'trial';
    }

    public function isInadimplente(): bool
    {
        return $this->status === 'inadimplente';
    }

    public function isCancelada(): bool
    {
        return $this->status === 'cancelada';
    }

    public function isExpirada(): bool
    {
        return $this->status === 'expirada';
    }

    // Logica

    public function emPeriodoTrial(): bool
    {
        return $this->isTrial()
            && $this->trial_termina_em
            && $this->trial_termina_em->isFuture();
    }

    public function trialExpirado(): bool
    {
        return $this->isTrial()
            && $this->trial_termina_em
            && $this->trial_termina_em->isPast();
    }

    public function podeUsarSistema(): bool
    {
        return $this->isAtiva() || $this->emPeriodoTrial();
    }

    public function podeCriarRegistros(): bool
    {
        return ($this->isAtiva() || $this->emPeriodoTrial()) && !$this->isInadimplente();
    }

    public function temFuncionalidade(string $key): bool
    {
        return $this->plano?->temFuncionalidade($key) ?? false;
    }

    // Accessors

    public function getValorAtualFormatadoAttribute(): string
    {
        return 'R$ ' . number_format($this->valor_atual, 2, ',', '.');
    }
}
