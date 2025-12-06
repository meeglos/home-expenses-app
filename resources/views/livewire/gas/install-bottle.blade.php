<div class="min-h-screen bg-gray-50">
  <!-- Header -->
  <div class="bg-linear-to-r sticky top-0 z-10 from-green-600 to-green-700 text-white shadow-lg">
    <div class="mx-auto max-w-7xl px-4 py-4">
      <div class="flex items-center justify-between">
        <div class="flex items-center">
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
          <h1 class="flex items-center gap-2 text-2xl font-bold">
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
                d="M12 6v6m0 0v6m0-6h6m-6 0H6"
              />
            </svg>
            Instalar Botella
          </h1>
        </div>

        <!-- User Dropdown -->
        <x-dropdown
          align="right"
          width="48"
        >
          <x-slot name="trigger">
            <button
              class="focus:outline-hidden inline-flex items-center rounded-md border border-transparent bg-white/10 px-3 py-2 text-sm font-medium leading-4 text-white transition duration-150 ease-in-out hover:bg-white/20"
            >
              <div>{{ Auth::user()->name }}</div>

              <div class="ms-1">
                <svg
                  class="h-4 w-4 fill-current"
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 20 20"
                >
                  <path
                    fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd"
                  />
                </svg>
              </div>
            </button>
          </x-slot>

          <x-slot name="content">
            <x-dropdown-link :href="route('profile.edit')">
              {{ __('Profile') }}
            </x-dropdown-link>

            <!-- Authentication -->
            <form
              method="POST"
              action="{{ route('logout') }}"
            >
              @csrf

              <x-dropdown-link
                :href="route('logout')"
                onclick="event.preventDefault();
                                                this.closest('form').submit();"
              >
                {{ __('Log Out') }}
              </x-dropdown-link>
            </form>
          </x-slot>
        </x-dropdown>
      </div>
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
        <h3 class="mb-4 flex items-center gap-2 text-lg font-bold">
          <svg
            class="h-5 w-5"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
            />
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
            />
          </svg>
          Ubicación
        </h3>
        <div class="grid grid-cols-2 gap-3">
          <label class="relative cursor-pointer">
            <input
              type="radio"
              wire:model.live="location"
              value="cocina"
              class="peer sr-only"
            >
            <div
              class="peer-checked:border-pocket-red-500 peer-checked:bg-pocket-red-50 rounded-lg border-2 p-4 text-center transition"
            >
              <x-gameicon-gas-stove class="mx-auto mb-2 h-8 w-8" />
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
              class="peer-checked:border-pocket-red-500 peer-checked:bg-pocket-red-50 rounded-lg border-2 p-4 text-center transition"
            >
              <x-gmdi-gas-meter-o class="mx-auto mb-2 h-8 w-8" />
              <div class="font-medium">Calentador</div>
            </div>
          </label>
        </div>
        @error('location')
          <span class="text-pocket-red-500 mt-1 text-sm">{{ $message }}</span>
        @enderror
      </div>

      <!-- Datos de la botella -->
      <div class="space-y-4 rounded-lg bg-white p-6 shadow-md">
        <h3 class="mb-4 flex items-center gap-2 text-lg font-bold">
          <svg
            class="h-5 w-5"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
            />
          </svg>
          Datos de la Botella
        </h3>

        <div>
          <label class="mb-2 block text-sm font-medium text-gray-700">
            Fecha y hora de instalación
          </label>
          <input
            type="datetime-local"
            wire:model="installed_at"
            class="border-pocket-gray-300 focus:ring-pocket-teal-500 w-full rounded-lg border px-4 py-3 focus:border-transparent focus:ring-2"
          >
          @error('installed_at')
            <span class="text-pocket-red-500 mt-1 text-sm">{{ $message }}</span>
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
            class="border-pocket-gray-300 focus:ring-pocket-teal-500 w-full rounded-lg border px-4 py-3 focus:border-transparent focus:ring-2"
            placeholder="12.5"
          >
          @error('weight_kg')
            <span class="text-pocket-red-500 mt-1 text-sm">{{ $message }}</span>
          @enderror
        </div>

        <div>
          <label class="mb-2 block text-sm font-medium text-gray-700">
            Notas (opcional)
          </label>
          <textarea
            wire:model="notes"
            rows="3"
            class="border-pocket-gray-300 focus:ring-pocket-teal-500 w-full rounded-lg border px-4 py-3 focus:border-transparent focus:ring-2"
            placeholder="Observaciones, estado de la botella, etc."
          ></textarea>
          @error('notes')
            <span class="text-pocket-red-500 mt-1 text-sm">{{ $message }}</span>
          @enderror
        </div>
      </div>

      <!-- Datos de compra -->
      <div class="space-y-4 rounded-lg bg-white p-6 shadow-md">
        <div class="mb-4 flex items-center justify-between">
          <h3 class="flex items-center gap-2 text-lg font-bold">
            <svg
              class="h-5 w-5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
            Datos de Compra
          </h3>
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
              class="border-pocket-gray-300 focus:ring-pocket-teal-500 w-full rounded-lg border px-4 py-3 focus:border-transparent focus:ring-2"
              placeholder="15.50"
            >
            @error('price')
              <span class="text-pocket-red-500 mt-1 text-sm">{{ $message }}</span>
            @enderror
          </div>

          <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">
              Fecha de compra
            </label>
            <input
              type="date"
              wire:model="purchase_date"
              class="border-pocket-gray-300 focus:ring-pocket-teal-500 w-full rounded-lg border px-4 py-3 focus:border-transparent focus:ring-2"
            >
            @error('purchase_date')
              <span class="text-pocket-red-500 mt-1 text-sm">{{ $message }}</span>
            @enderror
          </div>

          <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">
              Proveedor / Tienda (opcional)
            </label>
            <input
              type="text"
              wire:model="supplier"
              class="border-pocket-gray-300 focus:ring-pocket-teal-500 w-full rounded-lg border px-4 py-3 focus:border-transparent focus:ring-2"
              placeholder="Ej: Repsol, Cepsa..."
            >
            @error('supplier')
              <span class="text-pocket-red-500 mt-1 text-sm">{{ $message }}</span>
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
          class="bg-pocket-red-500 hover:bg-pocket-red-600 flex-1 rounded-lg px-6 py-4 font-bold text-white transition disabled:opacity-50"
        >
          <span wire:loading.remove>Guardar</span>
          <span wire:loading>Guardando...</span>
        </button>
      </div>
    </form>
  </div>
</div>
