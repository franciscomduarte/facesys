<?php

namespace App\Services;

use App\Models\Agendamento;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DisponibilidadeService
{
    public function getHorariosDisponiveis(Carbon $date, int $profissionalId, int $duracaoMinutos): array
    {
        $inicio = Carbon::createFromFormat('H:i', config('agenda.horario_inicio'));
        $fim = Carbon::createFromFormat('H:i', config('agenda.horario_fim'));
        $slotDuracao = config('agenda.duracao_slot_minutos', 30);

        $agendamentos = Agendamento::where('data_agendamento', $date->toDateString())
            ->where('profissional_id', $profissionalId)
            ->ativos()
            ->orderBy('hora_inicio')
            ->get(['hora_inicio', 'hora_fim']);

        $slots = [];
        $current = $inicio->copy();

        while ($current->copy()->addMinutes($duracaoMinutos)->lte($fim)) {
            $slotInicio = $current->format('H:i');
            $slotFim = $current->copy()->addMinutes($duracaoMinutos)->format('H:i');

            $conflito = $agendamentos->contains(function ($ag) use ($slotInicio, $slotFim) {
                return $slotInicio < $ag->hora_fim && $slotFim > $ag->hora_inicio;
            });

            if (!$conflito) {
                $slots[] = [
                    'hora_inicio' => $slotInicio,
                    'hora_fim' => $slotFim,
                    'label' => $slotInicio . ' - ' . $slotFim,
                ];
            }

            $current->addMinutes($slotDuracao);
        }

        return $slots;
    }

    public function temConflito(Carbon $date, string $horaInicio, string $horaFim, int $profissionalId, ?int $excludeId = null): bool
    {
        return Agendamento::where('data_agendamento', $date->toDateString())
            ->where('profissional_id', $profissionalId)
            ->ativos()
            ->where('hora_inicio', '<', $horaFim)
            ->where('hora_fim', '>', $horaInicio)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists();
    }

    public function calcularHoraFim(string $horaInicio, Collection $procedimentos): string
    {
        $duracaoTotal = $procedimentos->sum('duracao_media_minutos');

        if ($duracaoTotal === 0) {
            $duracaoTotal = config('agenda.duracao_slot_minutos', 30);
        }

        return Carbon::createFromFormat('H:i', $horaInicio)
            ->addMinutes($duracaoTotal)
            ->format('H:i');
    }
}
