<?php

namespace App\Models;

use App\Traits\AuditableTrait;
use App\Traits\BelongsToEmpresa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class TemplateContrato extends Model
{
    use HasFactory, SoftDeletes, AuditableTrait, BelongsToEmpresa, LogsActivity;

    protected $table = 'template_contratos';

    protected $fillable = [
        'nome',
        'descricao',
        'conteudo_template',
        'ativo',
    ];

    protected function casts(): array
    {
        return [
            'ativo' => 'boolean',
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

    public function contratos()
    {
        return $this->hasMany(Contrato::class);
    }

    // Scopes

    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }
}
