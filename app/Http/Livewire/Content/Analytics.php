<?php

namespace App\Http\Livewire\Content;

use App\Models\Visitor;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class Analytics extends Component
{
    public $totalVisitors = 0;
    public $todayVisitors = 0;
    public $thisWeekVisitors = 0;
    public $thisMonthVisitors = 0;
    public $topPages = [];
    public $visitorsByDay = [];
    public $isConnected = false;
    public $lastUpdate = null;
    public $onlineVisitors = 0;
    // Realtime indicators
    public $rtEvents = 0;
    public $rtLastAt = null;

    protected $listeners = ['realTimeUpdate' => 'handleRealTimeUpdate'];

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
        $this->visitorsByDay = Visitor::selectRaw('DATE(visit_date) as visit_date, COUNT(*) as count')
            ->whereBetween('visit_date', [now()->subDays(6)->startOfDay(), now()->endOfDay()])
            ->groupBy('visit_date')
            ->orderBy('visit_date')
            ->get();

        $this->lastUpdate = now()->format('H:i:s');
    }

    public function updateSocketServer()
    {
        // No-op in production (using polling fallback instead of socket server)
        return;
    }

    public function handleRealTimeUpdate($data)
    {
        // numbers
        // numbers (cast to int to ensure Blade prints reliably)
        if (array_key_exists('totalVisitors', $data))      $this->totalVisitors    = (int) $data['totalVisitors'];
        if (array_key_exists('todayVisitors', $data))      $this->todayVisitors    = (int) $data['todayVisitors'];
        if (array_key_exists('thisWeekVisitors', $data))   $this->thisWeekVisitors = (int) $data['thisWeekVisitors'];
        if (array_key_exists('thisMonthVisitors', $data))  $this->thisMonthVisitors = (int) $data['thisMonthVisitors'];
        if (array_key_exists('onlineVisitors', $data))     $this->onlineVisitors   = (int) $data['onlineVisitors'];

        // normalize arrays -> objects for Blade (`$x->field`)
        $tp = collect($data['topPages'] ?? [])
            ->map(function ($item) {
                return is_array($item) ? (object) $item : $item;
            });
        $vb = collect($data['visitorsByDay'] ?? [])
            ->map(function ($item) {
                return is_array($item) ? (object) $item : $item;
            });

        $this->topPages      = $tp;
        $this->visitorsByDay = $vb;

        $this->lastUpdate = now()->format('H:i:s');
        $this->rtEvents   = ($this->rtEvents ?? 0) + 1;
        $this->rtLastAt   = now()->format('H:i:s');
    }

    public function render()
    {
        return view('livewire.content.analytics');
    }
}
