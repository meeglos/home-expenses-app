<?php

namespace App\Livewire\Gas;

use App\Models\GasBottle;
use App\Models\GasBottleMove;
use App\Models\GasPurchase;
use App\Models\GasSupplierCall;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Activity extends Component
{
    public string $year_filter = '';

    protected $queryString = [
        'year_filter' => ['except' => ''],
    ];

    public function mount(): void
    {
        $this->year_filter = (string) now()->year;
    }

    /**
     * Obtiene el usuario autenticado
     */
    private function user(): User
    {
        $user = Auth::user();
        return $user;
    }

    public function render()
    {
        $userId = $this->user()->id;
        $year = $this->year_filter;

        // Llamadas
        $calls = GasSupplierCall::where('user_id', $userId)
            ->with('supplier')
            ->when($year, fn($q) => $q->whereYear('called_at', $year))
            ->get()
            ->map(fn($call) => [
                'type' => 'call',
                'date' => $call->called_at,
                'data' => $call,
            ]);

        // Compras
        $purchases = GasPurchase::where('user_id', $userId)
            ->with('supplier')
            ->when($year, fn($q) => $q->whereYear('purchase_date', $year))
            ->get()
            ->map(fn($purchase) => [
                'type' => 'purchase',
                'date' => $purchase->purchase_date,
                'data' => $purchase,
            ]);

        // Instalaciones
        $installations = GasBottle::where('user_id', $userId)
            ->when($year, fn($q) => $q->whereYear('installed_at', $year))
            ->get()
            ->map(fn($bottle) => [
                'type' => 'installation',
                'date' => $bottle->installed_at,
                'data' => $bottle,
            ]);

        // Movimientos
        $moves = GasBottleMove::whereHas('bottle', fn($q) => $q->where('user_id', $userId))
            ->with('bottle')
            ->when($year, fn($q) => $q->whereYear('moved_at', $year))
            ->get()
            ->map(fn($move) => [
                'type' => 'move',
                'date' => $move->moved_at,
                'data' => $move,
            ]);

        // Combinar y ordenar
        $activities = collect()
            ->merge($calls)
            ->merge($purchases)
            ->merge($installations)
            ->merge($moves)
            ->sortByDesc('date')
            ->values();

        // Años disponibles
        $years = collect([
            GasSupplierCall::where('user_id', $userId)->selectRaw('YEAR(called_at) as year')->distinct()->pluck('year'),
            GasPurchase::where('user_id', $userId)->selectRaw('YEAR(purchase_date) as year')->distinct()->pluck('year'),
            GasBottle::where('user_id', $userId)->selectRaw('YEAR(installed_at) as year')->distinct()->pluck('year'),
            GasBottleMove::whereHas('bottle', fn($q) => $q->where('user_id', $userId))->selectRaw('YEAR(moved_at) as year')->distinct()->pluck('year'),
        ])->flatten()->unique()->sort()->values();

        return view('livewire.gas.activity', [
            'activities' => $activities,
            'years' => $years,
        ]);
    }
}
