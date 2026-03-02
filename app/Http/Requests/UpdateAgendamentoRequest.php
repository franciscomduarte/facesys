<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAgendamentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'profissional_id' => 'required|exists:users,id',
            'data_agendamento' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'tipo_atendimento' => 'nullable|in:consulta,procedimento',
            'observacoes' => 'nullable|string',
            'procedimentos_selecionados' => 'required|array|min:1',
            'procedimentos_selecionados.*.id' => 'required|exists:procedimentos,id',
            'procedimentos_selecionados.*.quantidade' => 'nullable|numeric|min:0',
            'procedimentos_selecionados.*.observacoes' => 'nullable|string',
        ];
    }
}
