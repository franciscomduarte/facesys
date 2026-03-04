<?php

use Illuminate\Support\Facades\Schedule;

// ── Limpeza de dados ─────────────────────────────────

// Limpar registros antigos do activity log (> 365 dias, conforme config)
Schedule::command('activitylog:clean')->daily()->at('02:00');

// Limpar batches de jobs processados (> 48h)
Schedule::command('queue:prune-batches --hours=48')->daily()->at('02:30');

// Limpar failed jobs antigos (> 7 dias)
Schedule::command('queue:prune-failed --hours=168')->daily()->at('03:00');

// ── Manutencao de cache ──────────────────────────────

// Limpar tags stale do cache
Schedule::command('cache:prune-stale-tags')->hourly();
