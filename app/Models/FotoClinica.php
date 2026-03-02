<?php

namespace App\Models;

use App\Traits\AuditableTrait;
use App\Traits\BelongsToEmpresa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class FotoClinica extends Model
{
    use HasFactory, SoftDeletes, AuditableTrait, BelongsToEmpresa, LogsActivity;

    protected $table = 'fotos_clinicas';

    protected $fillable = [
        'patient_id',
        'treatment_session_id',
        'procedimento_id',
        'tipo',
        'caminho_arquivo',
        'nome_original',
        'mime_type',
        'tamanho_bytes',
        'data_registro',
        'profissional_responsavel',
        'observacoes',
        'regiao_facial',
        'ordem',
    ];

    protected function casts(): array
    {
        return [
            'data_registro' => 'date',
            'tamanho_bytes' => 'integer',
            'ordem' => 'integer',
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

    public function procedimento()
    {
        return $this->belongsTo(Procedimento::class);
    }

    // Accessors

    public function getUrlAttribute(): string
    {
        return Storage::disk('local')->url($this->caminho_arquivo);
    }

    public function getTamanhoFormatadoAttribute(): string
    {
        $bytes = $this->tamanho_bytes;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' MB';
        }
        return number_format($bytes / 1024, 0) . ' KB';
    }

    // Scopes

    public function scopeAntes($query)
    {
        return $query->where('tipo', 'antes');
    }

    public function scopeDepois($query)
    {
        return $query->where('tipo', 'depois');
    }

    public function scopeByProcedimento($query, int $procedimentoId)
    {
        return $query->where('procedimento_id', $procedimentoId);
    }
}
