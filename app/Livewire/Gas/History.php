<?php

namespace App\Livewire\Gas;

use App\Models\GasBottle;
use Livewire\Component;
use Livewire\WithPagination;

class History extends Component
{
    use WithPagination;

    public string $location_filter = 'all';
    public string $sort_by = 'installed_at';
    public string $sort_direction = 'desc';

    protected $queryString = [
        'location_filter' => ['except' => 'all'],
        'sort_by' => ['except' => 'installed_at'],
        'sort_direction' => ['except' => 'desc'],
    ];

    public function updatingLocationFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sort_by === $field) {
            $this->sort_direction = $this->sort_direction === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sort_by = $field;
            $this->sort_direction = 'desc';
        }
    }

    public function deleteBottle($bottleId)
    {
        $bottle = GasBottle::where('user_id', auth()->id())
            ->findOrFail($bottleId);

        $bottle->delete();

        session()->flash('success', 'Botella eliminada correctamente');
    }

    public function render()
    {
        $query = auth()->user()
            ->gasBottles()
            ->with('purchase');

        if ($this->location_filter !== 'all') {
            $query->where('location', $this->location_filter);
        }

        $bottles = $query->orderBy($this->sort_by, $this->sort_direction)
            ->paginate(20);

        return view('livewire.gas.history', [
            'bottles' => $bottles,
        ]);
    }
}
