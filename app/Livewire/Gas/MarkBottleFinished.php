<?php

namespace App\Livewire\Gas;

use App\Models\GasBottle;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class MarkBottleFinished extends Component
{
    public ?GasBottle $bottle = null;
    public bool $showModal = false;
    public string $finished_at = '';
    #[On('mark-finished')]
    public function openModal($bottleId)
    {
        $this->bottle = GasBottle::where('user_id', Auth::id())
            ->findOrFail($bottleId);

        $this->finished_at = now()->format('Y-m-d\TH:i');
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'finished_at' => 'required|date|after:' . $this->bottle->installed_at,
        ]);

        $this->bottle->markAsFinished(\Carbon\Carbon::parse($this->finished_at));

        session()->flash('success', 'Botella marcada como terminada correctamente');

        $this->reset(['bottle', 'showModal', 'finished_at']);
        $this->dispatch('$refresh');
    }

    public function closeModal()
    {
        $this->reset(['bottle', 'showModal', 'finished_at']);
    }

    public function render()
    {
        return view('livewire.gas.mark-bottle-finished');
    }
}
