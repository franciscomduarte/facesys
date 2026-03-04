<?php

namespace App\Services;

use App\Models\Empresa;
use App\Models\Subscription;
use Illuminate\Support\Facades\Cache;

class CacheService
{
    // TTLs em segundos
    private const TTL_EMPRESA = 1800;        // 30 min
    private const TTL_SUBSCRIPTION = 900;     // 15 min
    private const TTL_REFERENCIA = 3600;      // 1 hora
    private const TTL_DASHBOARD = 300;        // 5 min

    // ── Empresa ─────────────────────────────────────────

    public static function empresa(int $id): ?Empresa
    {
        return Cache::remember("empresa:{$id}", self::TTL_EMPRESA, function () use ($id) {
            return Empresa::find($id);
        });
    }

    public static function clearEmpresa(int $id): void
    {
        Cache::forget("empresa:{$id}");
    }

    // ── Subscription ────────────────────────────────────

    public static function subscription(int $empresaId): ?Subscription
    {
        return Cache::remember("subscription:empresa:{$empresaId}", self::TTL_SUBSCRIPTION, function () use ($empresaId) {
            return Subscription::where('empresa_id', $empresaId)
                ->whereNotIn('status', ['cancelada', 'expirada'])
                ->latest('created_at')
                ->first();
        });
    }

    public static function clearSubscription(int $empresaId): void
    {
        Cache::forget("subscription:empresa:{$empresaId}");
    }

    // ── Dados de Referencia (dropdowns) ─────────────────

    public static function profissionais(int $empresaId): mixed
    {
        return Cache::remember("profissionais:empresa:{$empresaId}", self::TTL_REFERENCIA, function () use ($empresaId) {
            return \App\Models\User::select('id', 'name')
                ->where('empresa_id', $empresaId)
                ->whereIn('role', ['admin', 'medico'])
                ->orderBy('name')
                ->get();
        });
    }

    public static function clearProfissionais(int $empresaId): void
    {
        Cache::forget("profissionais:empresa:{$empresaId}");
    }

    public static function procedimentosAtivos(int $empresaId): mixed
    {
        return Cache::remember("procedimentos_ativos:empresa:{$empresaId}", self::TTL_REFERENCIA, function () use ($empresaId) {
            return \App\Models\Procedimento::select('id', 'nome', 'categoria', 'duracao_media_minutos', 'valor_padrao')
                ->where('empresa_id', $empresaId)
                ->where('ativo', true)
                ->orderBy('categoria')
                ->orderBy('nome')
                ->get();
        });
    }

    public static function clearProcedimentosAtivos(int $empresaId): void
    {
        Cache::forget("procedimentos_ativos:empresa:{$empresaId}");
    }

    // ── Dashboard Admin ─────────────────────────────────

    public static function dashboardAdmin(string $key, \Closure $callback): mixed
    {
        return Cache::remember("dashboard:admin:{$key}", self::TTL_DASHBOARD, $callback);
    }

    public static function clearDashboardAdmin(): void
    {
        Cache::forget('dashboard:admin:empresas');
        Cache::forget('dashboard:admin:subscriptions');
        Cache::forget('dashboard:admin:mrr');
        Cache::forget('dashboard:admin:recentes');
        Cache::forget('dashboard:admin:atencao');
    }

    // ── Activity Log ────────────────────────────────────

    public static function activitySubjectTypes(): mixed
    {
        return Cache::remember('activity:subject_types', self::TTL_REFERENCIA, function () {
            return \Spatie\Activitylog\Models\Activity::distinct()
                ->whereNotNull('subject_type')
                ->pluck('subject_type');
        });
    }

    public static function clearActivitySubjectTypes(): void
    {
        Cache::forget('activity:subject_types');
    }
}
