<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Plano extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'nome',
        'slug',
        'descricao',
        'valor_mensal',
        'valor_anual',
        'periodicidade_padrao',
        'limite_usuarios',
        'limite_pacientes',
        'limite_agendamentos_mes',
        'funcionalidades',
        'trial_dias',
        'ativo',
        'ordem',
    ];

    protected $casts = [
        'funcionalidades' => 'array',
        'ativo' => 'boolean',
        'valor_mensal' => 'decimal:2',
        'valor_anual' => 'decimal:2',
        'trial_dias' => 'integer',
        'limite_usuarios' => 'integer',
        'limite_pacientes' => 'integer',
        'limite_agendamentos_mes' => 'integer',
        'ordem' => 'integer',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    // Relationships

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    // Helpers

    public function isGratuito(): bool
    {
        return $this->valor_mensal <= 0;
    }

    public function temTrial(): bool
    {
        return $this->trial_dias > 0;
    }

    public function temFuncionalidade(string $key): bool
    {
        return $this->funcionalidades[$key] ?? false;
    }

    public function isIlimitado(string $campo): bool
    {
        return ($this->{$campo} ?? 0) === -1;
    }

    // Scopes

    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    public function scopeOrdenados($query)
    {
        return $query->orderBy('ordem')->orderBy('valor_mensal');
    }

    // Accessors

    public function getValorMensalFormatadoAttribute(): string
    {
        return 'R$ ' . number_format($this->valor_mensal, 2, ',', '.');
    }

    public function getValorAnualFormatadoAttribute(): ?string
    {
        if (!$this->valor_anual) {
            return null;
        }

        return 'R$ ' . number_format($this->valor_anual, 2, ',', '.');
    }
}
