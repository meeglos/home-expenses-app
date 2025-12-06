<div>
  @if ($showModal)
    <!-- Overlay -->
    <div
      class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
      wire:click="closeModal"
    >
      <!-- Modal -->
      <div
        class="relative mx-4 w-full max-w-lg rounded-lg bg-white shadow-2xl"
        wire:click.stop
      >
        <!-- Header -->
        <div
          class="bg-pocket-red-500 flex items-center justify-between border-b px-6 py-4 text-white"
        >
          <h3 class="text-xl font-bold">🔄 Mover Botella de Ubicación</h3>
          <button
            wire:click="closeModal"
            class="text-white hover:text-gray-200"
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
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>

        <!-- Body -->
        <form wire:submit.prevent="moveBottle">
          <div class="space-y-4 p-6">
            <!-- Seleccionar Botella -->
            <div>
              <label
                for="bottle"
                class="mb-2 block font-medium text-gray-700"
              >Seleccionar Botella Activa</label>
              <select
                id="bottle"
                wire:model.live="selectedBottleId"
                class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring focus:ring-blue-200"
              >
                <option value="">-- Seleccione una botella --</option>
                @foreach ($this->activeBottles as $bottle)
                  <option value="{{ $bottle->id }}">
                    {{ ucfirst($bottle->location) }} -
                    {{ $bottle->weight_kg }}kg -
                    Instalada el {{ $bottle->installed_at->format('d/m/Y') }}
                    ({{ $bottle->days_elapsed }} días)
                  </option>
                @endforeach
              </select>
              @error('selectedBottleId')
                <p class="text-pocket-red-500 mt-1 text-sm">{{ $message }}</p>
              @enderror
            </div>

            @if ($selectedBottleId)
              @php
                $selectedBottle = $this->activeBottles->firstWhere('id', $selectedBottleId);
              @endphp

              @if ($selectedBottle)
                <!-- Info de la botella seleccionada -->
                <div class="bg-pocket-teal-50 rounded-lg p-4">
                  <div class="flex items-start space-x-3">
                    <div class="text-3xl">
                      @if ($selectedBottle->location === 'cocina')
                        <x-gameicon-gas-stove class="inline h-4 w-4" />
                      @else
                        <x-gmdi-gas-meter-o class="inline h-4 w-4" />
                      @endif
                    </div>
                    <div class="flex-1">
                      <p class="font-medium text-gray-800">
                        Ubicación actual: <span
                          class="text-pocket-teal-600">{{ ucfirst($selectedBottle->location) }}</span>
                      </p>
                      <p class="text-sm text-gray-600">
                        {{ $selectedBottle->weight_kg }}kg • {{ $selectedBottle->days_elapsed }}
                        días de uso
                      </p>
                    </div>
                  </div>
                </div>

                <!-- Nueva Ubicación -->
                <div>
                  <label
                    for="location"
                    class="mb-2 block font-medium text-gray-700"
                  >Mover a:</label>
                  <div class="grid grid-cols-2 gap-3">
                    <label
                      class="{{ $newLocation === 'cocina' ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:border-gray-400' }} flex cursor-pointer items-center justify-center space-x-2 rounded-lg border-2 px-4 py-3 transition"
                    >
                      <input
                        type="radio"
                        wire:model="newLocation"
                        value="cocina"
                        class="text-blue-600 focus:ring-blue-500"
                      >
                      <x-gameicon-gas-stove class="h-6 w-6" />
                      <span class="font-medium">Cocina</span>
                    </label>

                    <label
                      class="{{ $newLocation === 'calentador' ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:border-gray-400' }} flex cursor-pointer items-center justify-center space-x-2 rounded-lg border-2 px-4 py-3 transition"
                    >
                      <input
                        type="radio"
                        wire:model="newLocation"
                        value="calentador"
                        class="text-blue-600 focus:ring-blue-500"
                      >
                      <x-gmdi-gas-meter-o class="h-6 w-6" />
                      <span class="font-medium">Calentador</span>
                    </label>
                  </div>
                  @error('newLocation')
                    <p class="text-pocket-red-500 mt-1 text-sm">{{ $message }}</p>
                  @enderror
                </div>

                <!-- Razón del movimiento -->
                <div>
                  <label
                    for="reason"
                    class="mb-2 block font-medium text-gray-700"
                  >
                    Razón del movimiento <span class="text-sm text-gray-500">(opcional)</span>
                  </label>
                  <textarea
                    id="reason"
                    wire:model="reason"
                    rows="3"
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring focus:ring-blue-200"
                    placeholder="Ej: Emergencia, se olvidó comprar, prueba de consumo..."
                  ></textarea>
                  @error('reason')
                    <p class="text-pocket-red-500 mt-1 text-sm">{{ $message }}</p>
                  @enderror
                </div>
              @endif
            @endif
          </div>

          <!-- Footer -->
          <div class="flex justify-end space-x-3 border-t bg-gray-50 px-6 py-4">
            <button
              type="button"
              wire:click="closeModal"
              class="rounded-lg border border-gray-300 bg-white px-6 py-2 font-medium text-gray-700 transition hover:bg-gray-50"
            >
              Cancelar
            </button>
            <button
              type="submit"
              class="bg-pocket-red-500 hover:bg-pocket-red-600 rounded-lg px-6 py-2 font-medium text-white transition disabled:cursor-not-allowed disabled:opacity-50"
              {{ !$selectedBottleId || !$newLocation ? 'disabled' : '' }}
            >
              🔄 Mover Botella
            </button>
          </div>
        </form>
      </div>
    </div>
  @endif
</div>
