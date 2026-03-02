<?php

namespace App\Models;

use App\Traits\AuditableTrait;
use App\Traits\BelongsToEmpresa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ApplicationPoint extends Model
{
    use HasFactory, AuditableTrait, BelongsToEmpresa, LogsActivity;

    protected $fillable = [
        'treatment_session_id',
        'regiao_musculo',
        'unidades_aplicadas',
        'observacoes',
        'coord_x',
        'coord_y',
    ];

    protected function casts(): array
    {
        return [
            'unidades_aplicadas' => 'decimal:2',
            'coord_x' => 'decimal:4',
            'coord_y' => 'decimal:4',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function treatmentSession()
    {
        return $this->belongsTo(TreatmentSession::class);
    }
}
