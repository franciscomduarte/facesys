<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTreatmentSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'data_sessao' => ['required', 'date'],
            'procedimento' => ['required', 'string', 'max:255'],
            'marca_produto' => ['required', 'string', 'max:255'],
            'lote' => ['nullable', 'string', 'max:100'],
            'quantidade_total' => ['required', 'numeric', 'min:0.01'],
            'observacoes_sessao' => ['nullable', 'string'],
            'profissional_responsavel' => ['required', 'string', 'max:255'],

            'pontos' => ['nullable', 'array'],
            'pontos.*.regiao_musculo' => ['required_with:pontos', 'string', 'max:255'],
            'pontos.*.unidades_aplicadas' => ['required_with:pontos', 'numeric', 'min:0.01'],
            'pontos.*.observacoes' => ['nullable', 'string'],
            'pontos.*.coord_x' => ['required_with:pontos', 'numeric', 'min:0', 'max:100'],
            'pontos.*.coord_y' => ['required_with:pontos', 'numeric', 'min:0', 'max:100'],

            'procedimentos_selecionados' => ['nullable', 'array'],
            'procedimentos_selecionados.*.id' => ['required', 'exists:procedimentos,id'],
            'procedimentos_selecionados.*.quantidade' => ['nullable', 'numeric', 'min:0'],
            'procedimentos_selecionados.*.observacoes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'data_sessao.required' => 'A data da sessao e obrigatoria.',
            'procedimento.required' => 'O procedimento e obrigatorio.',
            'marca_produto.required' => 'A marca do produto e obrigatoria.',
            'quantidade_total.required' => 'A quantidade total e obrigatoria.',
            'quantidade_total.min' => 'A quantidade total deve ser maior que zero.',
            'profissional_responsavel.required' => 'O profissional responsavel e obrigatorio.',
        ];
    }
}
