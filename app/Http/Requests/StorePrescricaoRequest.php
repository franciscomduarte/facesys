<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePrescricaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'profissional_responsavel' => ['required', 'string', 'max:255'],
            'observacoes_gerais' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.medicamento' => ['required', 'string', 'max:255'],
            'items.*.dosagem' => ['required', 'string', 'max:255'],
            'items.*.via_administracao' => ['required', 'string', 'max:100'],
            'items.*.frequencia' => ['required', 'string', 'max:255'],
            'items.*.duracao' => ['required', 'string', 'max:255'],
            'items.*.observacoes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'profissional_responsavel.required' => 'O profissional responsavel e obrigatorio.',
            'items.required' => 'Adicione pelo menos um medicamento.',
            'items.min' => 'Adicione pelo menos um medicamento.',
            'items.*.medicamento.required' => 'O nome do medicamento e obrigatorio.',
            'items.*.dosagem.required' => 'A dosagem e obrigatoria.',
            'items.*.via_administracao.required' => 'A via de administracao e obrigatoria.',
            'items.*.frequencia.required' => 'A frequencia e obrigatoria.',
            'items.*.duracao.required' => 'A duracao e obrigatoria.',
        ];
    }
}
