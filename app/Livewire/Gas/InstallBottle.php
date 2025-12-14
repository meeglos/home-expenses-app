<?php

namespace App\Livewire\Gas;

use App\Models\GasBottle;
use App\Models\GasPurchase;
use App\Models\GasSupplier;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Validate;

class InstallBottle extends Component
{
    #[Validate('required|in:cocina,calentador')]
    public string $location = 'cocina';

    #[Validate('required|date')]
    public string $installed_at = '';

    #[Validate('nullable|numeric|min:0')]
    public ?float $weight_kg = 12.5;

    #[Validate('nullable|string|max:500')]
    public ?string $notes = null;

    // Botella comprada seleccionada (si existe)
    public ?int $selected_purchase_id = null;

    // Datos de compra
    public bool $add_purchase = false;

    #[Validate('nullable|numeric|min:0')]
    public ?float $price = null;

    #[Validate('nullable|date')]
    public ?string $purchase_date = null;

    #[Validate('nullable|exists:gas_suppliers,id')]
    public ?int $supplier_id = null;

    /**
     * Obtiene el usuario autenticado
     */
    private function user(): User
    {
        /** @var User $user */
        $user = Auth::user();
        return $user;
    }

    public function mount(): void
    {
        $this->installed_at = now()->format('Y-m-d\TH:i');
        $this->purchase_date = now()->format('Y-m-d\TH:i');

        // Verificar si hay compras sin instalar
        $uninstalledPurchases = $this->getUninstalledPurchases();

        // Si no hay compras sin instalar, mostrar formulario de compra
        if ($uninstalledPurchases->isEmpty()) {
            $this->add_purchase = true;
        }

        // Finalizar automáticamente la botella anterior en esta ubicación si existe
        $this->checkPreviousBottle();
    }

    /**
     * Obtiene las compras sin botella instalada
     */
    private function getUninstalledPurchases()
    {
        return GasPurchase::where('user_id', $this->user()->id)
            ->whereNull('gas_bottle_id')
            ->with('supplier')
            ->orderBy('purchase_date', 'desc')
            ->get();
    }

    /**
     * Cuando se selecciona una compra, cargar sus datos
     */
    public function updatedSelectedPurchaseId($value): void
    {
        if ($value) {
            $purchase = GasPurchase::find($value);
            if ($purchase) {
                $this->weight_kg = $purchase->weight_kg;
            }
        } else {
            $this->weight_kg = 12.5;
        }
    }

    private function checkPreviousBottle(): void
    {
        $previousBottle = GasBottle::where('user_id', $this->user()->id)
            ->where('location', $this->location)
            ->active()
            ->first();

        if ($previousBottle) {
            session()->flash('warning', "Hay una botella activa en {$this->location}. Se marcará como terminada automáticamente.");
        }
    }

    public function save()
    {
        $this->validate();

        $userId = $this->user()->id;

        // Finalizar botella anterior si existe
        $previousBottle = GasBottle::where('user_id', $userId)
            ->where('location', $this->location)
            ->active()
            ->first();

        if ($previousBottle) {
            $previousBottle->markAsFinished(now()->parse($this->installed_at));
        }

        // Crear nueva botella
        $bottle = GasBottle::create([
            'user_id' => $userId,
            'location' => $this->location,
            'weight_kg' => $this->weight_kg,
            'installed_at' => $this->installed_at,
            'notes' => $this->notes,
        ]);

        // Si seleccionó una compra existente, vincularla
        if ($this->selected_purchase_id) {
            $purchase = GasPurchase::find($this->selected_purchase_id);
            if ($purchase) {
                $purchase->gas_bottle_id = $bottle->id;
                $purchase->save();
            }
        }
        // Crear compra si se indicó y no hay compra seleccionada
        elseif ($this->add_purchase && $this->price) {
            GasPurchase::create([
                'user_id' => $userId,
                'gas_bottle_id' => $bottle->id,
                'price' => $this->price,
                'purchase_date' => $this->purchase_date ?? now(),
                'supplier_id' => $this->supplier_id,
                'weight_kg' => $this->weight_kg,
                'bottle_type' => 'recarga',
            ]);
        }

        session()->flash('success', 'Botella instalada correctamente');

        return redirect()->route('gas.dashboard');
    }

    public function render()
    {
        $uninstalledPurchases = $this->getUninstalledPurchases();
        $suppliers = GasSupplier::where('user_id', $this->user()->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('livewire.gas.install-bottle', [
            'uninstalledPurchases' => $uninstalledPurchases,
            'suppliers' => $suppliers,
        ]);
    }
}
