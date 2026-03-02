<?php

namespace App\Models;

use App\Traits\AuditableTrait;
use App\Traits\BelongsToEmpresa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Patient extends Model
{
    use HasFactory, SoftDeletes, AuditableTrait, BelongsToEmpresa, LogsActivity;

    protected $fillable = [
        'nome_completo',
        'data_nascimento',
        'sexo',
        'cpf',
        'telefone',
        'email',
        'endereco',
        'profissao',
        'observacoes_gerais',
        'historico_medico',
        'medicamentos_continuo',
        'alergias',
        'doencas_preexistentes',
        'contraindicacoes_esteticas',
        'observacoes_medicas',
    ];

    protected function casts(): array
    {
        return [
            'data_nascimento' => 'date',
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
        return $this->hasMany(TreatmentSession::class);
    }

    public function clinicalPhotos()
    {
        return $this->hasMany(FotoClinica::class);
    }

    public function prescricoes()
    {
        return $this->hasMany(Prescricao::class);
    }

    public function contratos()
    {
        return $this->hasMany(Contrato::class);
    }

    public function documentosAssinaveis()
    {
        return $this->hasMany(DocumentoAssinavel::class);
    }

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class);
    }
}
