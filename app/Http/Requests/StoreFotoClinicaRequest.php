<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFotoClinicaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'procedimento_id' => [
                'required',
                'integer',
                Rule::exists('procedimento_sessao', 'procedimento_id')
                    ->where('treatment_session_id', $this->route('session')->id),
            ],
            'tipo' => ['required', 'in:antes,depois'],
            'fotos' => ['required', 'array', 'min:1', 'max:10'],
            'fotos.*' => ['file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'data_registro' => ['required', 'date'],
            'profissional_responsavel' => ['required', 'string', 'max:255'],
            'observacoes' => ['nullable', 'string'],
            'regiao_facial' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'procedimento_id.required' => 'Selecione o procedimento.',
            'procedimento_id.exists' => 'O procedimento selecionado nao esta vinculado a esta sessao.',
            'tipo.required' => 'Selecione o tipo da foto (antes ou depois).',
            'tipo.in' => 'O tipo deve ser "antes" ou "depois".',
            'fotos.required' => 'Selecione pelo menos uma foto.',
            'fotos.min' => 'Selecione pelo menos uma foto.',
            'fotos.max' => 'Maximo de 10 fotos por envio.',
            'fotos.*.image' => 'O arquivo deve ser uma imagem.',
            'fotos.*.mimes' => 'Formato aceito: JPG, JPEG, PNG ou WebP.',
            'fotos.*.max' => 'Cada imagem deve ter no maximo 10MB.',
            'data_registro.required' => 'A data de registro e obrigatoria.',
            'profissional_responsavel.required' => 'O profissional responsavel e obrigatorio.',
        ];
    }
}
