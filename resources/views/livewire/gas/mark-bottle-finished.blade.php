<div>
  @if ($showModal)
    <div
      class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4"
      wire:click.self="closeModal"
    >
      <div
        class="w-full max-w-md rounded-lg bg-white p-6 shadow-xl"
        @click.stop
      >
        <h3 class="mb-4 text-xl font-bold">⏹️ Marcar Botella como Terminada</h3>

        @if ($bottle)
          <div class="mb-4 rounded bg-gray-50 p-3">
            <div class="mb-2 flex items-center gap-2">
              @if ($bottle->location === 'cocina')
                <x-gameicon-gas-stove class="h-8 w-8" />
              @else
                <x-gmdi-gas-meter-o class="h-8 w-8" />
              @endif
              <span class="font-bold">{{ ucfirst($bottle->location) }}</span>
            </div>
            <div class="text-sm text-gray-600">
              Instalada: {{ $bottle->installed_at->format('d/m/Y H:i') }}
            </div>
            <div class="text-sm text-gray-600">
              Días activa: {{ $bottle->days_elapsed }}
            </div>
          </div>

          <form
            wire:submit="save"
            class="space-y-4"
          >
            <div>
              <label class="mb-2 block text-sm font-medium text-gray-700">
                Fecha y hora de finalización
              </label>
              <input
                type="datetime-local"
                wire:model="finished_at"
                class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-transparent focus:ring-2 focus:ring-orange-500"
                required
              >
              @error('finished_at')
                <span class="text-pocket-red-500 mt-1 text-sm">{{ $message }}</span>
              @enderror
            </div>

            <div class="flex gap-3">
              <button
                type="button"
                wire:click="closeModal"
                class="flex-1 rounded-lg bg-gray-200 px-4 py-3 font-medium text-gray-800 transition hover:bg-gray-300"
              >
                Cancelar
              </button>
              <button
                type="submit"
                class="flex-1 rounded-lg bg-orange-600 px-4 py-3 font-medium text-white transition hover:bg-orange-700"
              >
                Confirmar
              </button>
            </div>
          </form>
        @endif
      </div>
  @endif
</div>
