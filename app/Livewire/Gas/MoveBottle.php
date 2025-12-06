<?php

namespace App\Livewire\Gas;

use App\Models\GasBottle;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MoveBottle extends Component
{
    public $showModal = false;
    public $selectedBottleId = null;
    public $newLocation = '';
    public $reason = '';

    protected $listeners = ['openMoveModal'];

    protected $rules = [
        'selectedBottleId' => 'required|exists:gas_bottles,id',
        'newLocation' => 'required|in:cocina,calentador',
        'reason' => 'nullable|string|max:500',
    ];

    protected $messages = [
        'selectedBottleId.required' => 'Debe seleccionar una botella',
        'selectedBottleId.exists' => 'La botella seleccionada no existe',
        'newLocation.required' => 'Debe seleccionar una ubicación de destino',
        'newLocation.in' => 'La ubicación debe ser cocina o calentador',
        'reason.max' => 'La razón no puede exceder 500 caracteres',
    ];

    public function openMoveModal()
    {
        $this->showModal = true;
        $this->reset(['selectedBottleId', 'newLocation', 'reason']);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['selectedBottleId', 'newLocation', 'reason']);
        $this->resetValidation();
    }

    public function updatedSelectedBottleId()
    {
        if ($this->selectedBottleId) {
            $bottle = GasBottle::find($this->selectedBottleId);
            if ($bottle) {
                // Sugerir la ubicación opuesta
                $this->newLocation = $bottle->location === 'cocina' ? 'calentador' : 'cocina';
            }
        }
    }

    public function moveBottle()
    {
        $this->validate();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $bottle = GasBottle::where('id', $this->selectedBottleId)
            ->where('user_id', $user->id)
            ->active()
            ->firstOrFail();

        try {
            $bottle->moveTo($this->newLocation, $this->reason);

            session()->flash('success', "✅ Botella movida de {$bottle->moves()->latest()->first()->from_location} a {$this->newLocation}");

            $this->closeModal();
            $this->dispatch('bottleMoved');
        } catch (\InvalidArgumentException $e) {
            $this->addError('newLocation', $e->getMessage());
        } catch (\Exception $e) {
            session()->flash('error', 'Error al mover la botella: ' . $e->getMessage());
        }
    }

    public function getActiveBottlesProperty()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return $user->gasBottles()
            ->active()
            ->with('purchase')
            ->orderBy('installed_at', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.gas.move-bottle');
    }
}
