<?php

namespace App\Http\Controllers;

use App\Services\CacheService;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $query = Activity::with('causer:id,name')->orderByDesc('created_at');

        if ($search = $request->input('search')) {
            $query->where('description', 'ilike', "%{$search}%");
        }

        if ($event = $request->input('event')) {
            $query->where('event', $event);
        }

        if ($subjectType = $request->input('subject_type')) {
            $query->where('subject_type', $subjectType);
        }

        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $activities = $query->paginate(25)->withQueryString();

        // Subject types disponiveis para filtro — cacheado por 1 hora
        $subjectTypes = CacheService::activitySubjectTypes()
            ->map(fn ($type) => [
                'value' => $type,
                'label' => class_basename($type),
            ]);

        return view('activity-logs.index', compact('activities', 'subjectTypes'));
    }
}
