<?php

namespace App\Models;

use App\Traits\AuditableTrait;
use App\Traits\BelongsToEmpresa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Agendamento extends Model
{
    use HasFactory, SoftDeletes, AuditableTrait, BelongsToEmpresa, LogsActivity;

    protected $table = 'agendamentos';

    protected $fillable = [
        'patient_id',
        'profissional_id',
        'treatment_session_id',
        'data_agendamento',
        'hora_inicio',
        'hora_fim',
        'status',
        'tipo_atendimento',
        'origem',
        'observacoes',
        'motivo_cancelamento',
    ];

    protected $casts = [
        'data_agendamento' => 'date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'data_agendamento', 'hora_inicio', 'hora_fim', 'motivo_cancelamento'])
            ->logOnlyDirty();
    }

    // Relationships

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function profissional(): BelongsTo
    {
        return $this->belongsTo(User::class, 'profissional_id');
    }

    public function treatmentSession(): BelongsTo
    {
        return $this->belongsTo(TreatmentSession::class);
    }

    public function procedimentos(): BelongsToMany
    {
        return $this->belongsToMany(Procedimento::class, 'agendamento_procedimento')
            ->withPivot('quantidade', 'observacoes');
    }

    // Helpers

    public function isAgendado(): bool
    {
        return $this->status === 'agendado';
    }

    public function isConfirmado(): bool
    {
        return $this->status === 'confirmado';
    }

    public function isCancelado(): bool
    {
        return $this->status === 'cancelado';
    }

    public function isRealizado(): bool
    {
        return $this->status === 'realizado';
    }

    public function isNaoCompareceu(): bool
    {
        return $this->status === 'nao_compareceu';
    }

    public function podeEditar(): bool
    {
        return in_array($this->status, ['agendado', 'confirmado']);
    }

    public function podeCancelar(): bool
    {
        return in_array($this->status, ['agendado', 'confirmado']);
    }

    // Scopes

    public function scopeDoDia($query, $date)
    {
        return $query->where('data_agendamento', $date);
    }

    public function scopeDoProfissional($query, int $profissionalId)
    {
        return $query->where('profissional_id', $profissionalId);
    }

    public function scopeAtivos($query)
    {
        return $query->whereNotIn('status', ['cancelado', 'nao_compareceu']);
    }

    public function scopeFuturos($query)
    {
        return $query->where('data_agendamento', '>=', now()->toDateString());
    }

    // Accessors

    public function getDuracaoEstimadaAttribute(): int
    {
        return $this->procedimentos->sum('duracao_media_minutos');
    }
}
