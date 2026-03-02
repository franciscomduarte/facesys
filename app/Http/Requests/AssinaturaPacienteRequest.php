<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssinaturaPacienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome_assinante' => ['required', 'string', 'max:255'],
            'documento_assinante' => ['required', 'string', 'size:11', 'regex:/^\d{11}$/'],
            'assinatura_imagem_base64' => ['required', 'string'],
            'consentimento' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome_assinante.required' => 'O nome completo e obrigatorio.',
            'documento_assinante.required' => 'O CPF e obrigatorio.',
            'documento_assinante.size' => 'O CPF deve conter 11 digitos.',
            'documento_assinante.regex' => 'O CPF deve conter apenas numeros.',
            'assinatura_imagem_base64.required' => 'A assinatura e obrigatoria. Desenhe sua assinatura no campo acima.',
            'consentimento.accepted' => 'Voce deve concordar com os termos para assinar.',
        ];
    }
}
