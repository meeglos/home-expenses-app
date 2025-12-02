<?php

namespace App\Livewire\Gas;

use App\Models\GasPurchase;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Purchases extends Component
{
    use WithPagination;

    public string $year_filter = '';
    public ?string $supplier_filter = null;

    public function mount()
    {
        $this->year_filter = now()->year;
    }

    public function updatingYearFilter()
    {
        $this->resetPage();
    }

    public function updatingSupplierFilter()
    {
        $this->resetPage();
    }

    public function getPriceEvolution()
    {
        return GasPurchase::where('user_id', auth()->id())
            ->select(
                DB::raw('DATE_FORMAT(purchase_date, "%Y-%m") as month'),
                DB::raw('AVG(price) as avg_price'),
                DB::raw('MIN(price) as min_price'),
                DB::raw('MAX(price) as max_price'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();
    }

    public function render()
    {
        $query = auth()->user()
            ->gasPurchases()
            ->with('gasBottle');

        if ($this->year_filter) {
            $query->whereYear('purchase_date', $this->year_filter);
        }

        if ($this->supplier_filter) {
            $query->where('supplier', $this->supplier_filter);
        }

        $purchases = $query->recent()->paginate(15);

        $stats = [
            'total' => GasPurchase::where('user_id', auth()->id())->sum('price'),
            'avg_price' => GasPurchase::where('user_id', auth()->id())->avg('price'),
            'this_year' => GasPurchase::where('user_id', auth()->id())
                ->whereYear('purchase_date', now()->year)
                ->sum('price'),
            'last_purchase' => GasPurchase::where('user_id', auth()->id())
                ->recent()
                ->first(),
        ];

        $suppliers = GasPurchase::where('user_id', auth()->id())
            ->whereNotNull('supplier')
            ->distinct()
            ->pluck('supplier');

        $years = GasPurchase::where('user_id', auth()->id())
            ->selectRaw('YEAR(purchase_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('livewire.gas.purchases', [
            'purchases' => $purchases,
            'stats' => $stats,
            'suppliers' => $suppliers,
            'years' => $years,
            'priceEvolution' => $this->getPriceEvolution(),
        ]);
    }
}
