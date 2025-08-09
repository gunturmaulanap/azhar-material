<?php

namespace App\Http\Livewire\Content;

use Livewire\Component;
use App\Models\Brand;
use App\Models\Goods;
use App\Models\HeroSection;
use App\Models\Project;
use App\Models\Analytics;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $brandsCount = 0;
    public $productsCount = 0;
    public $heroSectionsCount = 0;
    public $projectsCount = 0;
    public $totalVisitors = 0;
    public $todayVisitors = 0;
    public $visitorsByDay = [];

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        // Count records
        $this->brandsCount = Brand::count();
        $this->productsCount = Goods::count();
        $this->heroSectionsCount = HeroSection::count();
        $this->projectsCount = Project::count() ?? 0; // Will be 0 if table doesn't exist yet

        // Analytics data
        try {
            $this->totalVisitors = Analytics::count();
            $this->todayVisitors = Analytics::whereDate('created_at', today())->count();
            
            // Get visitors by day for the last 7 days
            $this->visitorsByDay = Analytics::select(
                DB::raw('DATE(created_at) as visit_date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('visit_date')
            ->orderBy('visit_date', 'desc')
            ->get()
            ->toArray();
        } catch (\Exception $e) {
            // Handle case where Analytics table doesn't exist yet
            $this->totalVisitors = 0;
            $this->todayVisitors = 0;
            $this->visitorsByDay = [];
        }
    }

    public function render()
    {
        return view('livewire.content.dashboard');
    }
}
