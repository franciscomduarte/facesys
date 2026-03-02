<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssinaturaProfissionalRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user && ($user->isAdmin() || $user->isMedico());
    }

    public function rules(): array
    {
        return [
            'nome_assinante' => ['required', 'string', 'max:255'],
            'documento_assinante' => ['required', 'string', 'size:11', 'regex:/^\d{11}$/'],
            'assinatura_imagem_base64' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome_assinante.required' => 'O nome completo e obrigatorio.',
            'documento_assinante.required' => 'O CPF e obrigatorio.',
            'documento_assinante.size' => 'O CPF deve conter 11 digitos.',
            'documento_assinante.regex' => 'O CPF deve conter apenas numeros.',
            'assinatura_imagem_base64.required' => 'A assinatura e obrigatoria.',
        ];
    }
}
