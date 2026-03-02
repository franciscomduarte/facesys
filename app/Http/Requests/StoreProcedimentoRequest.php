<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProcedimentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:255'],
            'categoria' => ['required', 'in:facial,corporal,capilar,outro'],
            'descricao_clinica' => ['required', 'string'],
            'indicacoes' => ['required', 'string'],
            'contraindicacoes' => ['required', 'string'],
            'duracao_media_minutos' => ['required', 'integer', 'min:1'],
            'ativo' => ['boolean'],
            'valor_padrao' => ['nullable', 'numeric', 'min:0'],
            'observacoes_internas' => ['nullable', 'string'],
            'termo_padrao' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome do procedimento e obrigatorio.',
            'categoria.required' => 'A categoria e obrigatoria.',
            'descricao_clinica.required' => 'A descricao clinica e obrigatoria.',
            'indicacoes.required' => 'As indicacoes sao obrigatorias.',
            'contraindicacoes.required' => 'As contraindicacoes sao obrigatorias.',
            'duracao_media_minutos.required' => 'A duracao media e obrigatoria.',
            'duracao_media_minutos.min' => 'A duracao deve ser de pelo menos 1 minuto.',
        ];
    }
}
