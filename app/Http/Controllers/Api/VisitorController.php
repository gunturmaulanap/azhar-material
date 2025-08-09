<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitorController extends Controller
{
    public function store(Request $request)
    {
        // token sederhana
        $token = $request->header('X-Track-Token');
        $expected = config('track.api_token');
        if ($expected && $token !== $expected) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $data = $request->validate([
            'visitor_id'   => 'required|string|max:100',
            'ip_address'   => 'nullable|string|max:45',
            'user_agent'   => 'nullable|string',
            'page_visited' => 'nullable|string|max:255',
            'referrer'     => 'nullable|string|max:255',
            'timestamp'    => 'nullable|date',
        ]);

        $ts = isset($data['timestamp']) ? Carbon::parse($data['timestamp']) : now();

        Visitor::create([
            'visitor_id'   => $data['visitor_id'],
            'ip_address'   => $data['ip_address'] ?? $request->ip(),
            'user_agent'   => $data['user_agent'] ?? $request->userAgent(),
            'page_visited' => $data['page_visited'] ?? '/',
            'referrer'     => $data['referrer'] ?? null,
            'visit_date'   => $ts->toDateString(),   // kolom DATE
            'visit_time'   => $ts->toTimeString(),   // kolom TIME
        ]);

        return response()->json(['stored' => true]);
    }

    public function snapshot(Request $request)
    {
        // token optional juga boleh dicek untuk GET kalau mau
        $today = Carbon::today();
        $startWeek = Carbon::now()->startOfWeek();
        $endWeek = Carbon::now()->endOfWeek();
        $startMonth = Carbon::now()->startOfMonth();
        $endMonth = Carbon::now()->endOfMonth();

        // DISTINCT visitor_id untuk unique visitor
        $totalVisitors = Visitor::distinct('visitor_id')->count('visitor_id');
        $todayVisitors = Visitor::whereDate('visit_date', $today)
            ->distinct('visitor_id')->count('visitor_id');
        $thisWeekVisitors = Visitor::whereBetween('visit_date', [$startWeek, $endWeek])
            ->distinct('visitor_id')->count('visitor_id');
        $thisMonthVisitors = Visitor::whereBetween('visit_date', [$startMonth, $endMonth])
            ->distinct('visitor_id')->count('visitor_id');

        // Top pages (hitung kunjungan)
        $topPages = Visitor::select('page_visited', DB::raw('COUNT(*) as count'))
            ->whereNotNull('page_visited')
            ->groupBy('page_visited')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // 7 hari terakhir (unique visitor per hari)
        $from = Carbon::now()->subDays(6)->startOfDay();
        $raw = Visitor::select(DB::raw('DATE(visit_date) as visit_date'), DB::raw('COUNT(DISTINCT visitor_id) as count'))
            ->whereBetween('visit_date', [$from, now()])
            ->groupBy(DB::raw('DATE(visit_date)'))
            ->orderBy('visit_date')
            ->get();

        // ensure setiap hari ada (meski 0)
        $map = $raw->keyBy('visit_date');
        $visitorsByDay = collect();
        for ($i = 6; $i >= 0; $i--) {
            $d = Carbon::now()->subDays($i)->toDateString();
            $visitorsByDay->push((object)[
                'visit_date' => $d,
                'count' => (int) ($map[$d]->count ?? 0),
            ]);
        }

        return response()->json([
            'totalVisitors'    => (int) $totalVisitors,
            'todayVisitors'    => (int) $todayVisitors,
            'thisWeekVisitors' => (int) $thisWeekVisitors,
            'thisMonthVisitors' => (int) $thisMonthVisitors,
            'topPages'         => $topPages,
            'visitorsByDay'    => $visitorsByDay,
        ]);
    }
}
