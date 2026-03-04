<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Subscription;
use App\Services\CacheService;

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
        // Metricas de empresas — 1 query consolidada com cache
        $empresasStats = CacheService::dashboardAdmin('empresas', function () {
            $stats = Empresa::selectRaw("
                count(*) as total,
                count(*) filter (where status = 'ativa') as ativas,
                count(*) filter (where status = 'suspensa') as suspensas,
                count(*) filter (where status = 'inativa') as inativas
            ")->first();

            return [
                'total' => $stats->total,
                'ativas' => $stats->ativas,
                'suspensas' => $stats->suspensas,
                'inativas' => $stats->inativas,
            ];
        });

        $empresasTotal = $empresasStats['total'];
        $empresasAtivas = $empresasStats['ativas'];
        $empresasSuspensas = $empresasStats['suspensas'];
        $empresasInativas = $empresasStats['inativas'];

        // Metricas de subscriptions — 1 query consolidada com cache
        $subscriptionsPorStatus = CacheService::dashboardAdmin('subscriptions', function () {
            return Subscription::selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();
        });

        $subscriptionsAtivas = ($subscriptionsPorStatus['ativa'] ?? 0) + ($subscriptionsPorStatus['trial'] ?? 0);

        // MRR — 1 query consolidada com cache
        $mrr = CacheService::dashboardAdmin('mrr', function () {
            $stats = Subscription::where('status', 'ativa')
                ->selectRaw("
                    coalesce(sum(case when periodicidade = 'mensal' then valor_atual else 0 end), 0) as mrr_mensal,
                    coalesce(sum(case when periodicidade = 'anual' then valor_atual / 12 else 0 end), 0) as mrr_anual
                ")
                ->first();

            return $stats->mrr_mensal + $stats->mrr_anual;
        });

        // Trials ativos com cache
        $trialsAtivos = CacheService::dashboardAdmin('trials', function () {
            return Subscription::where('status', 'trial')
                ->where(function ($q) {
                    $q->whereNull('trial_termina_em')
                      ->orWhere('trial_termina_em', '>=', now());
                })
                ->count();
        });

        // Ultimas empresas com cache
        $ultimasEmpresas = CacheService::dashboardAdmin('recentes', function () {
            return Empresa::with('subscription.plano')
                ->select('id', 'nome_fantasia', 'status', 'created_at')
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();
        });

        // Atencao necessaria com cache
        $atencaoNecessaria = CacheService::dashboardAdmin('atencao', function () {
            return Subscription::with(['empresa:id,nome_fantasia,status', 'plano:id,nome'])
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
        });

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
