<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Subscription;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->isSuperAdmin()) {
            return $this->adminDashboard();
        }

        return view('dashboard');
    }

    private function adminDashboard()
    {
        // Metricas de empresas
        $empresasTotal = Empresa::count();
        $empresasAtivas = Empresa::where('status', 'ativa')->count();
        $empresasSuspensas = Empresa::where('status', 'suspensa')->count();
        $empresasInativas = Empresa::where('status', 'inativa')->count();

        // Metricas de subscriptions
        $subscriptionsPorStatus = Subscription::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $subscriptionsAtivas = ($subscriptionsPorStatus['ativa'] ?? 0) + ($subscriptionsPorStatus['trial'] ?? 0);

        // MRR
        $mrrMensal = Subscription::where('status', 'ativa')
            ->where('periodicidade', 'mensal')
            ->sum('valor_atual');

        $mrrAnual = Subscription::where('status', 'ativa')
            ->where('periodicidade', 'anual')
            ->sum('valor_atual');

        $mrr = $mrrMensal + ($mrrAnual / 12);

        // Trials ativos
        $trialsAtivos = Subscription::where('status', 'trial')
            ->where(function ($q) {
                $q->whereNull('trial_termina_em')
                  ->orWhere('trial_termina_em', '>=', now());
            })
            ->count();

        // Ultimas empresas
        $ultimasEmpresas = Empresa::with('subscription.plano')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Atencao necessaria: inadimplentes + trials expirando em 7 dias
        $atencaoNecessaria = Subscription::with(['empresa', 'plano'])
            ->where(function ($q) {
                $q->where('status', 'inadimplente')
                  ->orWhere(function ($q2) {
                      $q2->where('status', 'trial')
                         ->whereNotNull('trial_termina_em')
                         ->whereBetween('trial_termina_em', [now(), now()->addDays(7)]);
                  })
                  ->orWhere(function ($q2) {
                      $q2->whereIn('status', ['ativa', 'trial'])
                         ->whereNotNull('proxima_cobranca')
                         ->whereBetween('proxima_cobranca', [now(), now()->addDays(7)]);
                  });
            })
            ->orderBy('proxima_cobranca')
            ->limit(10)
            ->get();

        return view('dashboard-admin', compact(
            'empresasTotal',
            'empresasAtivas',
            'empresasSuspensas',
            'empresasInativas',
            'subscriptionsPorStatus',
            'subscriptionsAtivas',
            'mrr',
            'trialsAtivos',
            'ultimasEmpresas',
            'atencaoNecessaria',
        ));
    }
}
