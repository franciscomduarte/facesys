<?php

namespace App\Models;

use App\Traits\AuditableTrait;
use App\Traits\BelongsToEmpresa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class TreatmentSession extends Model
{
    use HasFactory, SoftDeletes, AuditableTrait, BelongsToEmpresa, LogsActivity;

    protected $fillable = [
        'patient_id',
        'data_sessao',
        'procedimento',
        'marca_produto',
        'lote',
        'quantidade_total',
        'observacoes_sessao',
        'profissional_responsavel',
    ];

    protected function casts(): array
    {
        return [
            'data_sessao' => 'date',
            'quantidade_total' => 'decimal:2',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function applicationPoints()
    {
        return $this->hasMany(ApplicationPoint::class);
    }

    public function procedimentos()
    {
        return $this->belongsToMany(Procedimento::class, 'procedimento_sessao')
            ->withPivot('quantidade', 'observacoes')
            ->withTimestamps();
    }

    public function clinicalPhotos()
    {
        return $this->hasMany(FotoClinica::class);
    }

    public function prescricao()
    {
        return $this->hasOne(Prescricao::class);
    }

    public function contrato()
    {
        return $this->hasOne(Contrato::class);
    }
}
