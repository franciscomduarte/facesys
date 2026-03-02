<?php

namespace App\Services;

use App\Models\TreatmentSession;

class TemplateParserService
{
    public function parse(string $template, array $data): string
    {
        return preg_replace_callback('/\{\{(\s*[\w.]+\s*)\}\}/', function ($matches) use ($data) {
            $key = trim($matches[1]);
            return $data[$key] ?? $matches[0];
        }, $template);
    }

    public function buildDataFromSession(TreatmentSession $session): array
    {
        $session->load(['patient', 'procedimentos']);

        $patient = $session->patient;
        $valorTotal = 0;

        // Build procedures list
        $listaProcedimentos = '';
        $listaTexto = '';

        if ($session->procedimentos->count()) {
            $listaProcedimentos = '<table style="width:100%; border-collapse:collapse; margin:10px 0;">';
            $listaProcedimentos .= '<thead><tr>';
            $listaProcedimentos .= '<th style="border:1px solid #ccc; padding:6px; text-align:left;">Procedimento</th>';
            $listaProcedimentos .= '<th style="border:1px solid #ccc; padding:6px; text-align:center;">Qtd</th>';
            $listaProcedimentos .= '<th style="border:1px solid #ccc; padding:6px; text-align:right;">Valor Unit.</th>';
            $listaProcedimentos .= '<th style="border:1px solid #ccc; padding:6px; text-align:right;">Subtotal</th>';
            $listaProcedimentos .= '</tr></thead><tbody>';

            foreach ($session->procedimentos as $proc) {
                $quantidade = $proc->pivot->quantidade ?? 1;
                $valorUnit = $proc->valor_padrao ?? 0;
                $subtotal = $quantidade * $valorUnit;
                $valorTotal += $subtotal;

                $listaProcedimentos .= '<tr>';
                $listaProcedimentos .= '<td style="border:1px solid #ccc; padding:6px;">' . e($proc->nome) . '</td>';
                $listaProcedimentos .= '<td style="border:1px solid #ccc; padding:6px; text-align:center;">' . number_format($quantidade, 1, ',', '.') . '</td>';
                $listaProcedimentos .= '<td style="border:1px solid #ccc; padding:6px; text-align:right;">R$ ' . number_format($valorUnit, 2, ',', '.') . '</td>';
                $listaProcedimentos .= '<td style="border:1px solid #ccc; padding:6px; text-align:right;">R$ ' . number_format($subtotal, 2, ',', '.') . '</td>';
                $listaProcedimentos .= '</tr>';

                $listaTexto .= "- {$proc->nome} (Qtd: {$quantidade}, Valor: R$ " . number_format($subtotal, 2, ',', '.') . ")\n";

                if ($proc->pivot->observacoes) {
                    $listaTexto .= "  Obs: {$proc->pivot->observacoes}\n";
                }
            }

            $listaProcedimentos .= '</tbody></table>';
        }

        return [
            'paciente.nome' => $patient->nome_completo,
            'paciente.cpf' => $patient->cpf ?? '',
            'paciente.data_nascimento' => $patient->data_nascimento?->format('d/m/Y') ?? '',
            'paciente.endereco' => $patient->endereco ?? '',
            'paciente.telefone' => $patient->telefone ?? '',
            'profissional.nome' => $session->profissional_responsavel,
            'procedimentos.lista' => $listaProcedimentos,
            'procedimentos.lista_texto' => $listaTexto,
            'valor_total' => 'R$ ' . number_format($valorTotal, 2, ',', '.'),
            'data_atendimento' => $session->data_sessao->format('d/m/Y'),
            'clinica.nome' => 'LC Estetica',
            'clinica.cnpj' => '00.000.000/0001-00',
            'clinica.endereco' => 'Endereco da Clinica',
        ];
    }

    public function calculateValorTotal(TreatmentSession $session): float
    {
        $session->load('procedimentos');
        $total = 0;

        foreach ($session->procedimentos as $proc) {
            $quantidade = $proc->pivot->quantidade ?? 1;
            $total += $quantidade * ($proc->valor_padrao ?? 0);
        }

        return $total;
    }
}
