<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTemplateContratoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'conteudo_template' => ['required', 'string'],
            'ativo' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome do template e obrigatorio.',
            'conteudo_template.required' => 'O conteudo do template e obrigatorio.',
        ];
    }
}
