<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome_completo' => ['required', 'string', 'max:255'],
            'data_nascimento' => ['required', 'date', 'before:today'],
            'sexo' => ['required', 'in:masculino,feminino,outro'],
            'cpf' => ['required', 'string', 'size:14', 'unique:patients,cpf,' . $this->route('patient')->id],
            'telefone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'endereco' => ['nullable', 'string', 'max:500'],
            'profissao' => ['nullable', 'string', 'max:255'],
            'observacoes_gerais' => ['nullable', 'string'],
            'historico_medico' => ['nullable', 'string'],
            'medicamentos_continuo' => ['nullable', 'string'],
            'alergias' => ['nullable', 'string'],
            'doencas_preexistentes' => ['nullable', 'string'],
            'contraindicacoes_esteticas' => ['nullable', 'string'],
            'observacoes_medicas' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome_completo.required' => 'O nome completo e obrigatorio.',
            'data_nascimento.required' => 'A data de nascimento e obrigatoria.',
            'data_nascimento.before' => 'A data de nascimento deve ser anterior a hoje.',
            'sexo.required' => 'O sexo e obrigatorio.',
            'cpf.required' => 'O CPF e obrigatorio.',
            'cpf.size' => 'O CPF deve estar no formato 000.000.000-00.',
            'cpf.unique' => 'Este CPF ja esta cadastrado no sistema.',
            'telefone.required' => 'O telefone e obrigatorio.',
        ];
    }
}
