<?php

namespace App\Livewire\Gas;

use App\Models\GasSupplier;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Suppliers extends Component
{
    public $name = '';

    public $phone = '';

    public $email = '';

    public $notes = '';

    public $editingId = null;

    public $showForm = false;

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $supplier = GasSupplier::findOrFail($this->editingId);
            $supplier->update([
                'name' => $this->name,
                'phone' => $this->phone,
                'email' => $this->email,
                'notes' => $this->notes,
            ]);

            session()->flash('success', 'Proveedor actualizado correctamente.');
        } else {
            GasSupplier::create([
                'user_id' => Auth::id(),
                'name' => $this->name,
                'phone' => $this->phone,
                'email' => $this->email,
                'notes' => $this->notes,
            ]);

            session()->flash('success', 'Proveedor agregado correctamente.');
        }

        $this->resetForm();
    }

    public function edit($id)
    {
        $supplier = GasSupplier::findOrFail($id);

        $this->editingId = $id;
        $this->name = $supplier->name;
        $this->phone = $supplier->phone;
        $this->email = $supplier->email;
        $this->notes = $supplier->notes;
        $this->showForm = true;
    }

    public function delete($id)
    {
        GasSupplier::findOrFail($id)->delete();
        session()->flash('success', 'Proveedor eliminado correctamente.');
    }

    public function toggleActive($id)
    {
        $supplier = GasSupplier::findOrFail($id);
        $supplier->update(['is_active' => ! $supplier->is_active]);
    }

    public function resetForm()
    {
        $this->reset(['name', 'phone', 'email', 'notes', 'editingId', 'showForm']);
        $this->resetValidation();
    }

    public function render()
    {
        $suppliers = GasSupplier::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('livewire.gas.suppliers', [
            'suppliers' => $suppliers,
        ]);
    }
}
