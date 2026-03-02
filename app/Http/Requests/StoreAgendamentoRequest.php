<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAgendamentoRequest extends FormRequest
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
            'data_agendamento' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required|date_format:H:i',
            'tipo_atendimento' => 'nullable|in:consulta,procedimento',
            'observacoes' => 'nullable|string',
            'procedimentos_selecionados' => 'required|array|min:1',
            'procedimentos_selecionados.*.id' => 'required|exists:procedimentos,id',
            'procedimentos_selecionados.*.quantidade' => 'nullable|numeric|min:0',
            'procedimentos_selecionados.*.observacoes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'patient_id.required' => 'Selecione um paciente.',
            'profissional_id.required' => 'Selecione um profissional.',
            'data_agendamento.required' => 'Informe a data do agendamento.',
            'data_agendamento.after_or_equal' => 'A data deve ser hoje ou futura.',
            'hora_inicio.required' => 'Selecione um horario.',
            'procedimentos_selecionados.required' => 'Selecione ao menos um procedimento.',
            'procedimentos_selecionados.min' => 'Selecione ao menos um procedimento.',
        ];
    }
}
