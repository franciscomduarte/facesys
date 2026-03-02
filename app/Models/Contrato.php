<?php

namespace App\Models;

use App\Traits\AuditableTrait;
use App\Traits\BelongsToEmpresa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Contrato extends Model
{
    use HasFactory, SoftDeletes, AuditableTrait, BelongsToEmpresa, LogsActivity;

    protected $fillable = [
        'patient_id',
        'treatment_session_id',
        'profissional_id',
        'template_contrato_id',
        'status',
        'conteudo_renderizado',
        'hash_contrato',
        'valor_total',
        'observacoes',
        'data_geracao',
    ];

    protected function casts(): array
    {
        return [
            'data_geracao' => 'datetime',
            'valor_total' => 'decimal:2',
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

    public function profissional()
    {
        return $this->belongsTo(User::class, 'profissional_id');
    }

    public function templateContrato()
    {
        return $this->belongsTo(TemplateContrato::class);
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

    public function isGerado(): bool
    {
        return $this->status === 'gerado';
    }

    public function isAssinado(): bool
    {
        return $this->status === 'assinado';
    }

    public function podeEditar(): bool
    {
        return $this->isRascunho();
    }
}
