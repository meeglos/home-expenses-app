<?php

namespace App\Livewire\Gas;

use App\Models\GasBottle;
use App\Models\GasPurchase;
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

    // Datos de compra
    public bool $add_purchase = true;

    #[Validate('nullable|numeric|min:0')]
    public ?float $price = null;

    #[Validate('nullable|date')]
    public ?string $purchase_date = null;

    #[Validate('nullable|string|max:100')]
    public ?string $supplier = null;

    public function mount()
    {
        $this->installed_at = now()->format('Y-m-d\TH:i');
        $this->purchase_date = now()->format('Y-m-d');

        // Finalizar automáticamente la botella anterior en esta ubicación si existe
        $this->checkPreviousBottle();
    }

    private function checkPreviousBottle()
    {
        $previousBottle = GasBottle::where('user_id', auth()->id())
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

        // Finalizar botella anterior si existe
        $previousBottle = GasBottle::where('user_id', auth()->id())
            ->where('location', $this->location)
            ->active()
            ->first();

        if ($previousBottle) {
            $previousBottle->markAsFinished(now()->parse($this->installed_at));
        }

        // Crear nueva botella
        $bottle = GasBottle::create([
            'user_id' => auth()->id(),
            'location' => $this->location,
            'weight_kg' => $this->weight_kg,
            'installed_at' => $this->installed_at,
            'notes' => $this->notes,
        ]);

        // Crear compra si se indicó
        if ($this->add_purchase && $this->price) {
            GasPurchase::create([
                'user_id' => auth()->id(),
                'gas_bottle_id' => $bottle->id,
                'price' => $this->price,
                'purchase_date' => $this->purchase_date ?? now(),
                'supplier' => $this->supplier,
                'weight_kg' => $this->weight_kg,
                'bottle_type' => 'recarga',
            ]);
        }

        session()->flash('success', 'Botella instalada correctamente');

        return redirect()->route('gas.dashboard');
    }

    public function render()
    {
        return view('livewire.gas.install-bottle');
    }
}
