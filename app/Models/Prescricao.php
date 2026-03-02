<?php

namespace App\Models;

use App\Traits\AuditableTrait;
use App\Traits\BelongsToEmpresa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Prescricao extends Model
{
    use HasFactory, SoftDeletes, AuditableTrait, BelongsToEmpresa, LogsActivity;

    protected $table = 'prescricoes';

    protected $fillable = [
        'patient_id',
        'treatment_session_id',
        'data_emissao',
        'observacoes_gerais',
        'status',
        'profissional_responsavel',
    ];

    protected function casts(): array
    {
        return [
            'data_emissao' => 'date',
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

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function treatmentSession()
    {
        return $this->belongsTo(TreatmentSession::class);
    }

    public function items()
    {
        return $this->hasMany(PrescricaoItem::class)->orderBy('ordem');
    }

    public function documentosAssinaveis()
    {
        return $this->morphMany(DocumentoAssinavel::class, 'documento', 'documento_type', 'documento_id');
    }

    public function documentoAssinavelAtivo()
    {
        return $this->morphOne(DocumentoAssinavel::class, 'documento', 'documento_type', 'documento_id')
            ->whereNot('status', 'finalizado')
            ->latest();
    }

    // Helpers

    public function isRascunho(): bool
    {
        return $this->status === 'rascunho';
    }

    public function isEmitida(): bool
    {
        return $this->status === 'emitida';
    }

    public function isAssinada(): bool
    {
        return $this->status === 'assinada';
    }

    public function podeEditar(): bool
    {
        return $this->isRascunho();
    }
}
