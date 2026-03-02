<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Assinatura extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'assinaturas';

    protected $fillable = [
        'documento_assinavel_id',
        'tipo_assinatura',
        'nome_assinante',
        'documento_assinante',
        'ip',
        'user_agent',
        'data_assinatura',
        'hash_assinatura',
        'assinatura_imagem',
    ];

    protected function casts(): array
    {
        return [
            'data_assinatura' => 'datetime',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function documentoAssinavel()
    {
        return $this->belongsTo(DocumentoAssinavel::class);
    }

    public function isPaciente(): bool
    {
        return $this->tipo_assinatura === 'paciente';
    }

    public function isProfissional(): bool
    {
        return $this->tipo_assinatura === 'profissional';
    }
}
