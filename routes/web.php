<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\AssinaturaController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\FotoClinicaController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PlanoController;
use App\Http\Controllers\PrescricaoController;
use App\Http\Controllers\TemplateContratoController;
use App\Http\Controllers\TreatmentSessionController;
use App\Http\Controllers\ProcedimentoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

// Rotas publicas (Landing Page)
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/planos', [LandingController::class, 'planos'])->name('landing.planos');
Route::get('/contato', [LandingController::class, 'contato'])->name('landing.contato');
Route::get('/demo', [LandingController::class, 'demo'])->name('landing.demo');
Route::get('/termos-de-uso', [LandingController::class, 'termos'])->name('landing.termos');
Route::get('/politica-de-privacidade', [LandingController::class, 'privacidade'])->name('landing.privacidade');

// Rotas publicas de assinatura (sem auth)
Route::get('assinar/{token}', [AssinaturaController::class, 'assinarPaciente'])->name('assinatura.paciente');
Route::post('assinar/{token}', [AssinaturaController::class, 'storeAssinaturaPaciente'])->name('assinatura.paciente.store');
Route::get('verificar-documento', [AssinaturaController::class, 'verificar'])->name('assinatura.verificar');
Route::get('verificar-documento/{hash}', [AssinaturaController::class, 'verificarHash'])->name('assinatura.verificar.hash');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

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
    Route::get('patients/{patient}/sessions/{session}/fotos/{foto}', [FotoClinicaController::class, 'show'])
        ->name('patients.sessions.fotos.show');
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
    Route::patch('empresas/{empresa}/reset-admin', [EmpresaController::class, 'resetAdminAccess'])
        ->name('empresas.reset-admin');

    // Activity Logs (Super Admin)
    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

    // Planos (Super Admin)
    Route::resource('planos', PlanoController::class);
    Route::patch('planos/{plano}/toggle-ativo', [PlanoController::class, 'toggleAtivo'])
        ->name('planos.toggle-ativo');

    // Subscriptions (Super Admin)
    Route::resource('subscriptions', SubscriptionController::class)->except(['edit', 'update', 'destroy']);
    Route::patch('subscriptions/{subscription}/cancelar', [SubscriptionController::class, 'cancelar'])
        ->name('subscriptions.cancelar');
    Route::patch('subscriptions/{subscription}/reativar', [SubscriptionController::class, 'reativar'])
        ->name('subscriptions.reativar');
    Route::patch('subscriptions/{subscription}/alterar-plano', [SubscriptionController::class, 'alterarPlano'])
        ->name('subscriptions.alterar-plano');

    // Billing (Empresa)
    Route::get('meu-plano', [BillingController::class, 'show'])->name('billing.show');
    Route::get('planos-assinatura', [BillingController::class, 'plans'])->name('billing.plans');
    Route::get('checkout/{plano}', [BillingController::class, 'checkout'])->name('billing.checkout');
    Route::post('billing/pix', [BillingController::class, 'createPixPayment'])->name('billing.pix');
    Route::post('billing/card', [BillingController::class, 'createCardPayment'])->name('billing.card');
    Route::delete('billing/cancel', [BillingController::class, 'cancelSubscription'])->name('billing.cancel');
    Route::post('billing/change-plan', [BillingController::class, 'changePlan'])->name('billing.change-plan');
});

require __DIR__.'/auth.php';
