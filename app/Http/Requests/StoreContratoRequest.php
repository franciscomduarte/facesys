<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContratoRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user && ($user->isAdmin() || $user->isMedico());
    }

    public function rules(): array
    {
        return [
            'template_contrato_id' => ['required', 'exists:template_contratos,id'],
            'observacoes' => ['nullable', 'string'],
            'valor_total' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'template_contrato_id.required' => 'Selecione um template de contrato.',
            'template_contrato_id.exists' => 'O template selecionado nao existe.',
            'valor_total.numeric' => 'O valor deve ser numerico.',
            'valor_total.min' => 'O valor nao pode ser negativo.',
        ];
    }
}
