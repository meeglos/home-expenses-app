<div class="min-h-screen bg-gray-50">
  <!-- Header -->
  <div class="sticky top-0 z-10 bg-gradient-to-r from-green-600 to-green-700 text-white shadow-lg">
    <div class="mx-auto flex max-w-7xl items-center px-4 py-4">
      <a
        href="{{ route('gas.dashboard') }}"
        class="mr-3"
      >
        <svg
          class="h-6 w-6"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M15 19l-7-7 7-7"
          />
        </svg>
      </a>
      <h1 class="text-2xl font-bold">➕ Instalar Botella</h1>
    </div>
  </div>

  <div class="mx-auto max-w-2xl px-4 py-6">
    @if (session()->has('warning'))
      <div class="mb-4 rounded border-l-4 border-yellow-500 bg-yellow-100 p-4 text-yellow-700">
        ⚠️ {{ session('warning') }}
      </div>
    @endif

    <form
      wire:submit="save"
      class="space-y-6"
    >
      <!-- Ubicación -->
      <div class="rounded-lg bg-white p-6 shadow-md">
        <h3 class="mb-4 text-lg font-bold">📍 Ubicación</h3>
        <div class="grid grid-cols-2 gap-3">
          <label class="relative cursor-pointer">
            <input
              type="radio"
              wire:model.live="location"
              value="cocina"
              class="peer sr-only"
            >
            <div
              class="rounded-lg border-2 p-4 text-center transition peer-checked:border-blue-600 peer-checked:bg-blue-50"
            >
              <div class="mb-2 text-3xl">🍳</div>
              <div class="font-medium">Cocina</div>
            </div>
          </label>
          <label class="relative cursor-pointer">
            <input
              type="radio"
              wire:model.live="location"
              value="calentador"
              class="peer sr-only"
            >
            <div
              class="rounded-lg border-2 p-4 text-center transition peer-checked:border-blue-600 peer-checked:bg-blue-50"
            >
              <div class="mb-2 text-3xl">🚿</div>
              <div class="font-medium">Calentador</div>
            </div>
          </label>
        </div>
        @error('location')
          <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
        @enderror
      </div>

      <!-- Datos de la botella -->
      <div class="space-y-4 rounded-lg bg-white p-6 shadow-md">
        <h3 class="mb-4 text-lg font-bold">⛽ Datos de la Botella</h3>

        <div>
          <label class="mb-2 block text-sm font-medium text-gray-700">
            Fecha y hora de instalación
          </label>
          <input
            type="datetime-local"
            wire:model="installed_at"
            class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-transparent focus:ring-2 focus:ring-blue-500"
          >
          @error('installed_at')
            <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
          @enderror
        </div>

        <div>
          <label class="mb-2 block text-sm font-medium text-gray-700">
            Peso de la botella (kg)
          </label>
          <input
            type="number"
            step="0.1"
            wire:model="weight_kg"
            class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-transparent focus:ring-2 focus:ring-blue-500"
            placeholder="12.5"
          >
          @error('weight_kg')
            <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
          @enderror
        </div>

        <div>
          <label class="mb-2 block text-sm font-medium text-gray-700">
            Notas (opcional)
          </label>
          <textarea
            wire:model="notes"
            rows="3"
            class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-transparent focus:ring-2 focus:ring-blue-500"
            placeholder="Observaciones, estado de la botella, etc."
          ></textarea>
          @error('notes')
            <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
          @enderror
        </div>
      </div>

      <!-- Datos de compra -->
      <div class="space-y-4 rounded-lg bg-white p-6 shadow-md">
        <div class="mb-4 flex items-center justify-between">
          <h3 class="text-lg font-bold">💰 Datos de Compra</h3>
          <label class="flex cursor-pointer items-center">
            <input
              type="checkbox"
              wire:model.live="add_purchase"
              class="mr-2 h-5 w-5"
            >
            <span class="text-sm">Registrar compra</span>
          </label>
        </div>

        @if ($add_purchase)
          <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">
              Precio (€) *
            </label>
            <input
              type="number"
              step="0.01"
              wire:model="price"
              class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-transparent focus:ring-2 focus:ring-blue-500"
              placeholder="15.50"
            >
            @error('price')
              <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
            @enderror
          </div>

          <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">
              Fecha de compra
            </label>
            <input
              type="date"
              wire:model="purchase_date"
              class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-transparent focus:ring-2 focus:ring-blue-500"
            >
            @error('purchase_date')
              <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
            @enderror
          </div>

          <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">
              Proveedor / Tienda (opcional)
            </label>
            <input
              type="text"
              wire:model="supplier"
              class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-transparent focus:ring-2 focus:ring-blue-500"
              placeholder="Ej: Repsol, Cepsa..."
            >
            @error('supplier')
              <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
            @enderror
          </div>
        @else
          <p class="py-4 text-center text-sm text-gray-500">
            Puedes registrar la compra más tarde desde el historial
          </p>
        @endif
      </div>

      <!-- Botones -->
      <div class="flex gap-3">
        <a
          href="{{ route('gas.dashboard') }}"
          class="flex-1 rounded-lg bg-gray-200 px-6 py-4 text-center font-bold text-gray-800 transition hover:bg-gray-300"
        >
          Cancelar
        </a>
        <button
          type="submit"
          wire:loading.attr="disabled"
          class="flex-1 rounded-lg bg-green-600 px-6 py-4 font-bold text-white transition hover:bg-green-700 disabled:opacity-50"
        >
          <span wire:loading.remove>Guardar</span>
          <span wire:loading>Guardando...</span>
        </button>
      </div>
    </form>
  </div>
</div>
