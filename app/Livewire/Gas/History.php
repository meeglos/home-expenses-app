<?php

namespace App\Livewire\Gas;

use App\Models\GasBottle;
use App\Models\GasBottleMove;
use App\Models\GasPurchase;
use App\Models\GasSupplierCall;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class History extends Component
{
    use WithPagination;

    public string $location_filter = 'all';
    public string $sort_by = 'installed_at';
    public string $sort_direction = 'desc';
    public string $activeTab = 'history';
    public string $activity_year_filter = '';

    protected $queryString = [
        'location_filter' => ['except' => 'all'],
        'sort_by' => ['except' => 'installed_at'],
        'sort_direction' => ['except' => 'desc'],
        'tab' => ['except' => 'history', 'as' => 'activeTab'],
    ];

    public function mount(): void
    {
        $this->activity_year_filter = (string) now()->year;
    }

    /**
     * Obtiene el usuario autenticado
     */
    private function user(): User
    {
        $user = Auth::user();
        return $user;
    }

    public function updatingLocationFilter(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sort_by === $field) {
            $this->sort_direction = $this->sort_direction === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sort_by = $field;
            $this->sort_direction = 'desc';
        }
    }

    public function deleteBottle(int $bottleId): void
    {
        $bottle = GasBottle::where('user_id', $this->user()->id)
            ->findOrFail($bottleId);

        $bottle->delete();

        session()->flash('success', 'Botella eliminada correctamente');
    }

    public function render()
    {
        $query = $this->user()
            ->gasBottles()
            ->with(['purchase', 'moves']);

        if ($this->location_filter !== 'all') {
            $query->where('location', $this->location_filter);
        }

        $bottles = $query->orderBy($this->sort_by, $this->sort_direction)
            ->paginate(20);

        // Actividad combinada
        $activities = [];
        if ($this->activeTab === 'activity') {
            $userId = $this->user()->id;
            $year = $this->activity_year_filter;

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
        } else {
            $years = collect();
        }

        return view('livewire.gas.history', [
            'bottles' => $bottles,
            'activities' => $activities,
            'years' => $years,
        ]);
    }
}
