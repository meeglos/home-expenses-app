<?php

namespace App\Livewire\Gas;

use App\Models\GasBottle;
use App\Models\GasPurchase;
use Livewire\Component;
use Livewire\Attributes\Computed;

class Dashboard extends Component
{
    public string $activeTab = 'overview';

    /**
     * Botellas activas
     */
    #[Computed]
    public function activeBottles()
    {
        return auth()->user()
            ->gasBottles()
            ->active()
            ->with('purchase')
            ->orderBy('installed_at', 'desc')
            ->get();
    }

    /**
     * Estadísticas generales
     */
    #[Computed]
    public function statistics()
    {
        $userId = auth()->id();

        $stats = [
            'cocina' => $this->getLocationStats('cocina'),
            'calentador' => $this->getLocationStats('calentador'),
            'total_spent' => GasPurchase::where('user_id', $userId)
                ->sum('price'),
            'total_bottles' => GasBottle::where('user_id', $userId)->count(),
            'recent_purchases' => GasPurchase::where('user_id', $userId)
                ->recent()
                ->limit(5)
                ->get(),
        ];

        return $stats;
    }

    private function getLocationStats(string $location): array
    {
        $bottles = GasBottle::where('user_id', auth()->id())
            ->where('location', $location)
            ->finished()
            ->whereNotNull('duration_days')
            ->get();

        if ($bottles->isEmpty()) {
            return [
                'avg_duration' => 0,
                'min_duration' => 0,
                'max_duration' => 0,
                'avg_daily_usage' => 0,
                'total_bottles' => 0,
                'current_active' => null,
            ];
        }

        $currentActive = GasBottle::where('user_id', auth()->id())
            ->where('location', $location)
            ->active()
            ->first();

        return [
            'avg_duration' => round($bottles->avg('duration_days'), 1),
            'min_duration' => $bottles->min('duration_days'),
            'max_duration' => $bottles->max('duration_days'),
            'avg_daily_usage' => round($bottles->avg('estimated_daily_usage'), 3),
            'total_bottles' => $bottles->count(),
            'current_active' => $currentActive,
        ];
    }

    public function render()
    {
        return view('livewire.gas.dashboard');
    }
}
