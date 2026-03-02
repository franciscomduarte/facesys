<?php

namespace App\Models;

use App\Traits\AuditableTrait;
use App\Traits\BelongsToEmpresa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Procedimento extends Model
{
    use HasFactory, SoftDeletes, AuditableTrait, BelongsToEmpresa, LogsActivity;

    protected $fillable = [
        'nome',
        'categoria',
        'descricao_clinica',
        'indicacoes',
        'contraindicacoes',
        'duracao_media_minutos',
        'ativo',
        'valor_padrao',
        'observacoes_internas',
        'termo_padrao',
    ];

    protected function casts(): array
    {
        return [
            'ativo' => 'boolean',
            'valor_padrao' => 'decimal:2',
            'duracao_media_minutos' => 'integer',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function treatmentSessions()
    {
        return $this->belongsToMany(TreatmentSession::class, 'procedimento_sessao')
            ->withPivot('quantidade', 'observacoes')
            ->withTimestamps();
    }

    public function clinicalPhotos()
    {
        return $this->hasMany(FotoClinica::class);
    }

    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    public function scopeByCategoria($query, string $categoria)
    {
        return $query->where('categoria', $categoria);
    }
}
