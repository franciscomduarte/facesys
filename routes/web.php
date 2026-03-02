<?php

use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\AssinaturaController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\FotoClinicaController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PrescricaoController;
use App\Http\Controllers\TemplateContratoController;
use App\Http\Controllers\TreatmentSessionController;
use App\Http\Controllers\ProcedimentoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Rotas publicas de assinatura (sem auth)
Route::get('assinar/{token}', [AssinaturaController::class, 'assinarPaciente'])->name('assinatura.paciente');
Route::post('assinar/{token}', [AssinaturaController::class, 'storeAssinaturaPaciente'])->name('assinatura.paciente.store');
Route::get('verificar-documento', [AssinaturaController::class, 'verificar'])->name('assinatura.verificar');
Route::get('verificar-documento/{hash}', [AssinaturaController::class, 'verificarHash'])->name('assinatura.verificar.hash');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Pacientes
    Route::resource('patients', PatientController::class);

    // Sessoes de Atendimento (nested)
    Route::resource('patients.sessions', TreatmentSessionController::class)
        ->except(['index'])
        ->parameters(['sessions' => 'session']);

    // Fotos Clinicas (nested sob sessions)
    Route::post('patients/{patient}/sessions/{session}/fotos', [FotoClinicaController::class, 'store'])
        ->name('patients.sessions.fotos.store');
    Route::delete('patients/{patient}/sessions/{session}/fotos/{foto}', [FotoClinicaController::class, 'destroy'])
        ->name('patients.sessions.fotos.destroy');

    // Prescricoes (nested sob sessions)
    Route::get('patients/{patient}/sessions/{session}/prescricao/create', [PrescricaoController::class, 'create'])
        ->name('patients.sessions.prescricao.create');
    Route::post('patients/{patient}/sessions/{session}/prescricao', [PrescricaoController::class, 'store'])
        ->name('patients.sessions.prescricao.store');
    Route::get('patients/{patient}/sessions/{session}/prescricao/{prescricao}', [PrescricaoController::class, 'show'])
        ->name('patients.sessions.prescricao.show');
    Route::get('patients/{patient}/sessions/{session}/prescricao/{prescricao}/edit', [PrescricaoController::class, 'edit'])
        ->name('patients.sessions.prescricao.edit');
    Route::put('patients/{patient}/sessions/{session}/prescricao/{prescricao}', [PrescricaoController::class, 'update'])
        ->name('patients.sessions.prescricao.update');
    Route::delete('patients/{patient}/sessions/{session}/prescricao/{prescricao}', [PrescricaoController::class, 'destroy'])
        ->name('patients.sessions.prescricao.destroy');
    Route::patch('patients/{patient}/sessions/{session}/prescricao/{prescricao}/emitir', [PrescricaoController::class, 'emitir'])
        ->name('patients.sessions.prescricao.emitir');
    Route::get('patients/{patient}/sessions/{session}/prescricao/{prescricao}/pdf', [PrescricaoController::class, 'pdf'])
        ->name('patients.sessions.prescricao.pdf');

    // Assinaturas (autenticadas)
    Route::post('patients/{patient}/sessions/{session}/prescricao/{prescricao}/solicitar-assinatura', [AssinaturaController::class, 'solicitarAssinatura'])
        ->name('patients.sessions.prescricao.solicitar-assinatura');
    Route::get('documentos/{documento}/assinar-profissional', [AssinaturaController::class, 'assinarProfissional'])
        ->name('assinatura.profissional');
    Route::post('documentos/{documento}/assinar-profissional', [AssinaturaController::class, 'storeAssinaturaProfissional'])
        ->name('assinatura.profissional.store');
    Route::get('documentos/{documento}/assinaturas', [AssinaturaController::class, 'show'])
        ->name('assinatura.show');
    Route::patch('documentos/{documento}/regenerar-token', [AssinaturaController::class, 'regenerarToken'])
        ->name('assinatura.regenerar-token');

    // Contratos (nested sob sessions)
    Route::get('patients/{patient}/sessions/{session}/contrato/create', [ContratoController::class, 'create'])
        ->name('patients.sessions.contrato.create');
    Route::post('patients/{patient}/sessions/{session}/contrato', [ContratoController::class, 'store'])
        ->name('patients.sessions.contrato.store');
    Route::get('patients/{patient}/sessions/{session}/contrato/{contrato}', [ContratoController::class, 'show'])
        ->name('patients.sessions.contrato.show');
    Route::get('patients/{patient}/sessions/{session}/contrato/{contrato}/edit', [ContratoController::class, 'edit'])
        ->name('patients.sessions.contrato.edit');
    Route::put('patients/{patient}/sessions/{session}/contrato/{contrato}', [ContratoController::class, 'update'])
        ->name('patients.sessions.contrato.update');
    Route::delete('patients/{patient}/sessions/{session}/contrato/{contrato}', [ContratoController::class, 'destroy'])
        ->name('patients.sessions.contrato.destroy');
    Route::patch('patients/{patient}/sessions/{session}/contrato/{contrato}/gerar', [ContratoController::class, 'gerar'])
        ->name('patients.sessions.contrato.gerar');
    Route::get('patients/{patient}/sessions/{session}/contrato/{contrato}/pdf', [ContratoController::class, 'pdf'])
        ->name('patients.sessions.contrato.pdf');
    Route::post('patients/{patient}/sessions/{session}/contrato/{contrato}/solicitar-assinatura', [ContratoController::class, 'solicitarAssinatura'])
        ->name('patients.sessions.contrato.solicitar-assinatura');

    // Templates de Contrato
    Route::resource('templates-contrato', TemplateContratoController::class);
    Route::patch('templates-contrato/{templates_contrato}/toggle-ativo', [TemplateContratoController::class, 'toggleAtivo'])
        ->name('templates-contrato.toggle-ativo');

    // Procedimentos
    Route::resource('procedimentos', ProcedimentoController::class);
    Route::patch('procedimentos/{procedimento}/toggle-ativo', [ProcedimentoController::class, 'toggleAtivo'])
        ->name('procedimentos.toggle-ativo');

    // Agendamentos
    Route::get('agendamentos/horarios-disponiveis', [AgendamentoController::class, 'horariosDisponiveis'])
        ->name('agendamentos.horarios-disponiveis');
    Route::resource('agendamentos', AgendamentoController::class);
    Route::patch('agendamentos/{agendamento}/confirmar', [AgendamentoController::class, 'confirmar'])
        ->name('agendamentos.confirmar');
    Route::patch('agendamentos/{agendamento}/cancelar', [AgendamentoController::class, 'cancelar'])
        ->name('agendamentos.cancelar');
    Route::patch('agendamentos/{agendamento}/nao-compareceu', [AgendamentoController::class, 'noShow'])
        ->name('agendamentos.nao-compareceu');

    // Empresas (Super Admin)
    Route::resource('empresas', EmpresaController::class);
    Route::patch('empresas/{empresa}/toggle-status', [EmpresaController::class, 'toggleStatus'])
        ->name('empresas.toggle-status');
});

require __DIR__.'/auth.php';
