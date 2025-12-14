<?php

namespace App\Livewire\Gas;

use App\Models\GasPurchase;
use App\Models\GasSupplier;
use App\Models\GasSupplierCall;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class Purchases extends Component
{
    use WithPagination;

    public string $year_filter = '';
    public ?string $supplier_filter = null;

    // Propiedades del formulario
    public float $price = 0;
    public string $purchase_date = '';
    public ?int $supplier_id = null;
    public float $weight_kg = 12.5;
    public string $bottle_type = 'recarga';
    public int $quantity = 1;
    public ?string $notes = null;

    public function mount(): void
    {
        $this->year_filter = (string) now()->year;
        $this->purchase_date = now()->format('Y-m-d\TH:i');
    }

    public function resetForm(): void
    {
        $this->price = 0;
        $this->purchase_date = now()->format('Y-m-d\TH:i');
        $this->supplier_id = null;
        $this->weight_kg = 12.5;
        $this->bottle_type = 'recarga';
        $this->quantity = 1;
        $this->notes = null;
    }

    public function registerCall(int $supplierId): void
    {
        GasSupplierCall::create([
            'user_id' => $this->userId(),
            'supplier_id' => $supplierId,
            'called_at' => now(),
        ]);

        session()->flash('call_registered', 'Llamada registrada');
    }

    public function savePurchase(): void
    {
        $validated = $this->validate([
            'price' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
            'supplier_id' => 'nullable|exists:gas_suppliers,id',
            'weight_kg' => 'required|numeric|min:0',
            'bottle_type' => 'required|in:nueva,recarga',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        // Crear una o más compras según la cantidad
        for ($i = 0; $i < $this->quantity; $i++) {
            GasPurchase::create([
                'user_id' => $this->userId(),
                'price' => $validated['price'],
                'purchase_date' => $validated['purchase_date'],
                'supplier_id' => $validated['supplier_id'],
                'weight_kg' => $validated['weight_kg'],
                'bottle_type' => $validated['bottle_type'],
                'notes' => $validated['notes'],
            ]);
        }

        $message = $this->quantity > 1
            ? "Se registraron {$this->quantity} compras correctamente"
            : 'Compra registrada correctamente';

        session()->flash('success', $message);

        $this->resetForm();
        $this->resetPage();
    }

    /**
     * Obtiene el usuario autenticado
     */
    private function user(): User
    {
        /** @var User $user */
        $user = Auth::user();
        return $user;
    }

    /**
     * Obtiene el ID del usuario autenticado
     */
    private function userId(): int
    {
        return $this->user()->id;
    }

    public function updatingYearFilter()
    {
        $this->resetPage();
    }

    public function updatingSupplierFilter()
    {
        $this->resetPage();
    }

    /**
     * Obtiene la evolución de precios por mes
     * 
     * @return Collection<int, GasPurchase>
     */
    public function getPriceEvolution(): Collection
    {
        return GasPurchase::where('user_id', $this->userId())
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
        $query = $this->user()
            ->gasPurchases()
            ->with(['gasBottle', 'supplier']);

        if ($this->year_filter) {
            $query->whereYear('purchase_date', $this->year_filter);
        }

        if ($this->supplier_filter) {
            $query->where('supplier_id', $this->supplier_filter);
        }

        $purchases = $query->recent()->paginate(15);

        $userId = $this->userId();

        $stats = [
            'total' => GasPurchase::where('user_id', $userId)->sum('price'),
            'avg_price' => GasPurchase::where('user_id', $userId)->avg('price'),
            'this_year' => GasPurchase::where('user_id', $userId)
                ->whereYear('purchase_date', now()->year)
                ->sum('price'),
            'last_purchase' => GasPurchase::where('user_id', $userId)
                ->recent()
                ->first(),
        ];

        $activeSuppliers = GasSupplier::where('user_id', $userId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $usedSuppliers = GasSupplier::where('user_id', $userId)
            ->whereHas('purchases')
            ->orderBy('name')
            ->get();

        $years = GasPurchase::where('user_id', $userId)
            ->selectRaw('YEAR(purchase_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $recentCalls = GasSupplierCall::where('user_id', $userId)
            ->with('supplier')
            ->orderBy('called_at', 'desc')
            ->limit(5)
            ->get();

        return view('livewire.gas.purchases', [
            'purchases' => $purchases,
            'stats' => $stats,
            'activeSuppliers' => $activeSuppliers,
            'usedSuppliers' => $usedSuppliers,
            'years' => $years,
            'priceEvolution' => $this->getPriceEvolution(),
            'recentCalls' => $recentCalls,
        ]);
    }
}
