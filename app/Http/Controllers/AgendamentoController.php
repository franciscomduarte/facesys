<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Patient;
use App\Models\Procedimento;
use App\Models\User;
use App\Services\AgendamentoService;
use App\Services\DisponibilidadeService;
use App\Http\Requests\StoreAgendamentoRequest;
use App\Http\Requests\UpdateAgendamentoRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AgendamentoController extends Controller
{
    public function __construct(
        private AgendamentoService $agendamentoService,
        private DisponibilidadeService $disponibilidadeService,
    ) {}

    public function index(Request $request)
    {
        $dataBase = $request->get('data')
            ? Carbon::parse($request->get('data'))->startOfWeek()
            : now()->startOfWeek();

        $profissionalId = $request->get('profissional_id');

        $agendamentosSemana = $this->agendamentoService->getByWeek($dataBase, $profissionalId);

        $profissionais = User::whereIn('role', ['admin', 'medico'])->orderBy('name')->get();

        $dias = [];
        for ($i = 0; $i < 6; $i++) {
            $dia = $dataBase->copy()->addDays($i);
            $dias[] = [
                'data' => $dia,
                'label' => $dia->translatedFormat('D d/m'),
                'agendamentos' => $agendamentosSemana->filter(
                    fn($ag) => $ag->data_agendamento->toDateString() === $dia->toDateString()
                )->values(),
            ];
        }

        return view('agendamentos.index', compact('dias', 'dataBase', 'profissionais', 'profissionalId'));
    }

    public function create()
    {
        $patients = Patient::orderBy('nome_completo')->get(['id', 'nome_completo']);
        $profissionais = User::whereIn('role', ['admin', 'medico'])->orderBy('name')->get(['id', 'name']);
        $procedimentosAtivos = Procedimento::ativos()->orderBy('categoria')->orderBy('nome')->get();

        return view('agendamentos.create', compact('patients', 'profissionais', 'procedimentosAtivos'));
    }

    public function store(StoreAgendamentoRequest $request)
    {
        $validated = $request->validated();
        $procedimentos = $validated['procedimentos_selecionados'];
        unset($validated['procedimentos_selecionados']);

        $procIds = collect($procedimentos)->pluck('id')->toArray();
        $procs = Procedimento::whereIn('id', $procIds)->get();
        $horaFim = $this->disponibilidadeService->calcularHoraFim($validated['hora_inicio'], $procs);

        if ($this->disponibilidadeService->temConflito(
            Carbon::parse($validated['data_agendamento']),
            $validated['hora_inicio'],
            $horaFim,
            $validated['profissional_id']
        )) {
            return back()->withInput()->with('error', 'Conflito de horario: ja existe um agendamento neste horario para este profissional.');
        }

        $agendamento = $this->agendamentoService->create($validated, $procedimentos);

        return redirect()
            ->route('agendamentos.show', $agendamento)
            ->with('success', 'Agendamento criado com sucesso.');
    }

    public function show(Agendamento $agendamento)
    {
        $agendamento->load(['patient', 'profissional', 'procedimentos', 'treatmentSession']);

        return view('agendamentos.show', compact('agendamento'));
    }

    public function edit(Agendamento $agendamento)
    {
        $this->authorize('update', $agendamento);

        $agendamento->load(['patient', 'profissional', 'procedimentos']);
        $patients = Patient::orderBy('nome_completo')->get(['id', 'nome_completo']);
        $profissionais = User::whereIn('role', ['admin', 'medico'])->orderBy('name')->get(['id', 'name']);
        $procedimentosAtivos = Procedimento::ativos()->orderBy('categoria')->orderBy('nome')->get();

        return view('agendamentos.edit', compact('agendamento', 'patients', 'profissionais', 'procedimentosAtivos'));
    }

    public function update(UpdateAgendamentoRequest $request, Agendamento $agendamento)
    {
        $this->authorize('update', $agendamento);

        $validated = $request->validated();
        $procedimentos = $validated['procedimentos_selecionados'];
        unset($validated['procedimentos_selecionados']);

        $procIds = collect($procedimentos)->pluck('id')->toArray();
        $procs = Procedimento::whereIn('id', $procIds)->get();
        $horaFim = $this->disponibilidadeService->calcularHoraFim($validated['hora_inicio'], $procs);

        if ($this->disponibilidadeService->temConflito(
            Carbon::parse($validated['data_agendamento']),
            $validated['hora_inicio'],
            $horaFim,
            $validated['profissional_id'],
            $agendamento->id
        )) {
            return back()->withInput()->with('error', 'Conflito de horario: ja existe um agendamento neste horario para este profissional.');
        }

        $this->agendamentoService->update($agendamento, $validated, $procedimentos);

        return redirect()
            ->route('agendamentos.show', $agendamento)
            ->with('success', 'Agendamento atualizado com sucesso.');
    }

    public function destroy(Agendamento $agendamento)
    {
        $this->authorize('delete', $agendamento);

        $this->agendamentoService->delete($agendamento);

        return redirect()
            ->route('agendamentos.index')
            ->with('success', 'Agendamento removido com sucesso.');
    }

    public function confirmar(Agendamento $agendamento)
    {
        $this->authorize('confirmar', $agendamento);

        $this->agendamentoService->confirmar($agendamento);

        return back()->with('success', 'Agendamento confirmado com sucesso.');
    }

    public function cancelar(Request $request, Agendamento $agendamento)
    {
        $this->authorize('cancelar', $agendamento);

        $motivo = $request->input('motivo_cancelamento');
        $this->agendamentoService->cancelar($agendamento, $motivo);

        return back()->with('success', 'Agendamento cancelado.');
    }

    public function noShow(Agendamento $agendamento)
    {
        $this->authorize('marcarNaoCompareceu', $agendamento);

        $this->agendamentoService->marcarNaoCompareceu($agendamento);

        return back()->with('success', 'Agendamento marcado como nao compareceu.');
    }

    public function horariosDisponiveis(Request $request)
    {
        $request->validate([
            'data' => 'required|date',
            'profissional_id' => 'required|exists:users,id',
            'duracao' => 'required|integer|min:1',
        ]);

        $slots = $this->disponibilidadeService->getHorariosDisponiveis(
            Carbon::parse($request->data),
            (int) $request->profissional_id,
            (int) $request->duracao
        );

        return response()->json($slots);
    }
}
