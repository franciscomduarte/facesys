<?php

namespace App\Services;

use App\Models\Agendamento;
use App\Models\Procedimento;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AgendamentoService
{
    public function __construct(
        private DisponibilidadeService $disponibilidadeService,
        private NotificacaoAgendamentoService $notificacaoService,
    ) {}

    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return Agendamento::query()
            ->with(['patient', 'profissional', 'procedimentos'])
            ->when($filters['data'] ?? null, fn($q, $data) => $q->where('data_agendamento', $data))
            ->when($filters['profissional_id'] ?? null, fn($q, $id) => $q->where('profissional_id', $id))
            ->when($filters['status'] ?? null, fn($q, $s) => $q->where('status', $s))
            ->when($filters['patient_id'] ?? null, fn($q, $id) => $q->where('patient_id', $id))
            ->when(isset($filters['search']) && $filters['search'], function ($q) use ($filters) {
                $search = $filters['search'];
                $q->whereHas('patient', fn($pq) => $pq->where('nome_completo', 'ilike', "%{$search}%"));
            })
            ->orderBy('data_agendamento')
            ->orderBy('hora_inicio')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getByDate(Carbon $date, ?int $profissionalId = null): Collection
    {
        return Agendamento::query()
            ->with(['patient', 'profissional', 'procedimentos'])
            ->doDia($date)
            ->when($profissionalId, fn($q) => $q->doProfissional($profissionalId))
            ->orderBy('hora_inicio')
            ->get();
    }

    public function getByWeek(Carbon $startDate, ?int $profissionalId = null): Collection
    {
        $endDate = $startDate->copy()->addDays(6);

        return Agendamento::query()
            ->with(['patient', 'profissional', 'procedimentos'])
            ->whereBetween('data_agendamento', [$startDate->toDateString(), $endDate->toDateString()])
            ->when($profissionalId, fn($q) => $q->doProfissional($profissionalId))
            ->orderBy('data_agendamento')
            ->orderBy('hora_inicio')
            ->get();
    }

    public function create(array $data, array $procedimentos): Agendamento
    {
        return DB::transaction(function () use ($data, $procedimentos) {
            $procIds = collect($procedimentos)->pluck('id')->toArray();
            $procs = Procedimento::whereIn('id', $procIds)->get();

            $horaFim = $this->disponibilidadeService->calcularHoraFim($data['hora_inicio'], $procs);
            $data['hora_fim'] = $horaFim;

            $agendamento = Agendamento::create($data);

            $syncData = [];
            foreach ($procedimentos as $proc) {
                if (!empty($proc['id'])) {
                    $syncData[$proc['id']] = [
                        'quantidade' => $proc['quantidade'] ?? null,
                        'observacoes' => $proc['observacoes'] ?? null,
                    ];
                }
            }
            $agendamento->procedimentos()->sync($syncData);

            $agendamento->load(['patient', 'profissional', 'procedimentos']);
            $this->notificacaoService->notificarNovo($agendamento);

            return $agendamento;
        });
    }

    public function update(Agendamento $agendamento, array $data, array $procedimentos): Agendamento
    {
        return DB::transaction(function () use ($agendamento, $data, $procedimentos) {
            $dataOriginal = $agendamento->data_agendamento->toDateString();
            $horaOriginal = $agendamento->hora_inicio;

            $procIds = collect($procedimentos)->pluck('id')->toArray();
            $procs = Procedimento::whereIn('id', $procIds)->get();

            $horaFim = $this->disponibilidadeService->calcularHoraFim($data['hora_inicio'], $procs);
            $data['hora_fim'] = $horaFim;

            $dataMudou = ($data['data_agendamento'] ?? $dataOriginal) !== $dataOriginal
                || ($data['hora_inicio'] ?? $horaOriginal) !== $horaOriginal;

            if ($dataMudou) {
                $data['origem'] = 'remarcacao';
            }

            $agendamento->update($data);

            $syncData = [];
            foreach ($procedimentos as $proc) {
                if (!empty($proc['id'])) {
                    $syncData[$proc['id']] = [
                        'quantidade' => $proc['quantidade'] ?? null,
                        'observacoes' => $proc['observacoes'] ?? null,
                    ];
                }
            }
            $agendamento->procedimentos()->sync($syncData);

            $agendamento->load(['patient', 'profissional', 'procedimentos']);

            if ($dataMudou) {
                $this->notificacaoService->notificarRemarcacao($agendamento);
            }

            return $agendamento->fresh(['patient', 'profissional', 'procedimentos']);
        });
    }

    public function confirmar(Agendamento $agendamento): Agendamento
    {
        $agendamento->update(['status' => 'confirmado']);
        $agendamento->load(['patient', 'profissional']);
        $this->notificacaoService->notificarConfirmacao($agendamento);

        return $agendamento;
    }

    public function cancelar(Agendamento $agendamento, ?string $motivo = null): Agendamento
    {
        $agendamento->update([
            'status' => 'cancelado',
            'motivo_cancelamento' => $motivo,
        ]);
        $agendamento->load(['patient', 'profissional']);
        $this->notificacaoService->notificarCancelamento($agendamento);

        return $agendamento;
    }

    public function marcarNaoCompareceu(Agendamento $agendamento): Agendamento
    {
        $agendamento->update(['status' => 'nao_compareceu']);
        return $agendamento;
    }

    public function marcarRealizado(Agendamento $agendamento): Agendamento
    {
        $agendamento->update(['status' => 'realizado']);
        return $agendamento;
    }

    public function delete(Agendamento $agendamento): bool
    {
        return $agendamento->delete();
    }
}
