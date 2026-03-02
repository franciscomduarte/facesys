<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescricaoItem extends Model
{
    use HasFactory;

    protected $table = 'prescricao_items';

    protected $fillable = [
        'prescricao_id',
        'medicamento',
        'dosagem',
        'via_administracao',
        'frequencia',
        'duracao',
        'observacoes',
        'ordem',
    ];

    protected function casts(): array
    {
        return [
            'ordem' => 'integer',
        ];
    }

    public function prescricao()
    {
        return $this->belongsTo(Prescricao::class);
    }
}
