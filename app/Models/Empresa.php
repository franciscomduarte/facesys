<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Empresa extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'nome_fantasia',
        'razao_social',
        'cnpj',
        'email',
        'telefone',
        'status',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Relationships

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class);
    }

    public function procedimentos(): HasMany
    {
        return $this->hasMany(Procedimento::class);
    }

    public function agendamentos(): HasMany
    {
        return $this->hasMany(Agendamento::class);
    }

    public function templateContratos(): HasMany
    {
        return $this->hasMany(TemplateContrato::class);
    }

    // Helpers

    public function isAtiva(): bool
    {
        return $this->status === 'ativa';
    }

    public function isInativa(): bool
    {
        return $this->status === 'inativa';
    }

    public function isSuspensa(): bool
    {
        return $this->status === 'suspensa';
    }

    // Scopes

    public function scopeAtivas($query)
    {
        return $query->where('status', 'ativa');
    }
}
