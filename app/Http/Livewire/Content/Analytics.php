<?php

namespace App\Http\Livewire\Content;

use App\Models\Visitor;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class Analytics extends Component
{
    public $totalVisitors;
    public $todayVisitors;
    public $thisWeekVisitors;
    public $thisMonthVisitors;
    public $topPages;
    public $visitorsByDay;
    public $isConnected = false;
    public $lastUpdate;

    protected $listeners = ['echo:analytics,analytics-update' => 'handleRealTimeUpdate'];

    public function mount()
    {
        $this->loadAnalytics();
        $this->updateSocketServer();
    }

    public function loadAnalytics()
    {
        // Total visitors
        $this->totalVisitors = Visitor::count();

        // Today's visitors
        $this->todayVisitors = Visitor::today()->count();

        // This week's visitors
        $this->thisWeekVisitors = Visitor::thisWeek()->count();

        // This month's visitors
        $this->thisMonthVisitors = Visitor::thisMonth()->count();

        // Top pages
        $this->topPages = Visitor::selectRaw('page_visited, COUNT(*) as count')
            ->whereNotNull('page_visited')
            ->groupBy('page_visited')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        // Visitors by day (last 7 days)
        $this->visitorsByDay = Visitor::selectRaw('visit_date, COUNT(*) as count')
            ->whereBetween('visit_date', [now()->subDays(6), now()])
            ->groupBy('visit_date')
            ->orderBy('visit_date')
            ->get();

        $this->lastUpdate = now()->format('H:i:s');
    }

    public function updateSocketServer()
    {
        try {
            $response = Http::post('http://localhost:3001/api/analytics/update', [
                'totalVisitors' => $this->totalVisitors,
                'todayVisitors' => $this->todayVisitors,
                'thisWeekVisitors' => $this->thisWeekVisitors,
                'thisMonthVisitors' => $this->thisMonthVisitors,
                'topPages' => $this->topPages->toArray(),
                'visitorsByDay' => $this->visitorsByDay->toArray(),
            ]);

            if ($response->successful()) {
                $this->isConnected = true;
            }
        } catch (\Exception $e) {
            $this->isConnected = false;
        }
    }

    public function handleRealTimeUpdate($data)
    {
        $this->totalVisitors = $data['totalVisitors'] ?? $this->totalVisitors;
        $this->todayVisitors = $data['todayVisitors'] ?? $this->todayVisitors;
        $this->thisWeekVisitors = $data['thisWeekVisitors'] ?? $this->thisWeekVisitors;
        $this->thisMonthVisitors = $data['thisMonthVisitors'] ?? $this->thisMonthVisitors;
        $this->topPages = collect($data['topPages'] ?? []);
        $this->visitorsByDay = collect($data['visitorsByDay'] ?? []);
        $this->lastUpdate = now()->format('H:i:s');
    }

    public function render()
    {
        return view('livewire.content.analytics');
    }
}
