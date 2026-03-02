<?php

namespace App\Models;

use App\Traits\AuditableTrait;
use App\Traits\BelongsToEmpresa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class DocumentoAssinavel extends Model
{
    use HasFactory, SoftDeletes, AuditableTrait, BelongsToEmpresa, LogsActivity;

    protected $table = 'documentos_assinaveis';

    protected $fillable = [
        'tipo_documento',
        'documento_id',
        'documento_type',
        'patient_id',
        'profissional_id',
        'status',
        'hash_documento',
        'token_acesso',
        'token_expira_em',
    ];

    protected function casts(): array
    {
        return [
            'token_expira_em' => 'datetime',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // Relationships

    public function documento()
    {
        return $this->morphTo('documento', 'documento_type', 'documento_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function profissional()
    {
        return $this->belongsTo(User::class, 'profissional_id');
    }

    public function assinaturas()
    {
        return $this->hasMany(Assinatura::class);
    }

    public function assinaturaPaciente()
    {
        return $this->hasOne(Assinatura::class)->where('tipo_assinatura', 'paciente');
    }

    public function assinaturaProfissional()
    {
        return $this->hasOne(Assinatura::class)->where('tipo_assinatura', 'profissional');
    }

    // Helpers

    public function isPendente(): bool
    {
        return $this->status === 'pendente';
    }

    public function isAssinadoPaciente(): bool
    {
        return $this->status === 'assinado_paciente';
    }

    public function isAssinadoProfissional(): bool
    {
        return $this->status === 'assinado_profissional';
    }

    public function isFinalizado(): bool
    {
        return $this->status === 'finalizado';
    }

    public function tokenExpirado(): bool
    {
        return $this->token_expira_em->isPast();
    }

    public function podeAssinarPaciente(): bool
    {
        return $this->isPendente() && !$this->tokenExpirado();
    }

    public function podeAssinarProfissional(): bool
    {
        return $this->isAssinadoPaciente();
    }
}
