<div class="min-h-screen bg-gray-50">
  <!-- Header -->
  <div class="sticky top-0 z-10 bg-gradient-to-r from-purple-600 to-purple-700 text-white shadow-lg">
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
      <h1 class="text-2xl font-bold">📋 Historial</h1>
    </div>
  </div>

  <div class="mx-auto max-w-7xl px-4 py-6">
    @if (session()->has('success'))
      <div class="mb-4 rounded border-l-4 border-green-500 bg-green-100 p-4 text-green-700">
        ✓ {{ session('success') }}
      </div>
    @endif

    <!-- Filtros -->
    <div class="mb-4 rounded-lg bg-white p-4 shadow-md">
      <div class="flex flex-wrap gap-3">
        <div class="min-w-[150px] flex-1">
          <label class="mb-1 block text-sm font-medium text-gray-700">Ubicación</label>
          <select
            wire:model.live="location_filter"
            class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-purple-500"
          >
            <option value="all">Todas</option>
            <option value="cocina">🍳 Cocina</option>
            <option value="calentador">🚿 Calentador</option>
          </select>
        </div>
        <div class="min-w-[150px] flex-1">
          <label class="mb-1 block text-sm font-medium text-gray-700">Ordenar por</label>
          <select
            wire:model.live="sort_by"
            class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-purple-500"
          >
            <option value="installed_at">Fecha instalación</option>
            <option value="finished_at">Fecha finalización</option>
            <option value="duration_days">Duración</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Lista de botellas -->
    <div class="space-y-3">
      @forelse ($bottles as $bottle)
        @php
          $icon = $bottle->location === 'cocina' ? '🍳' : '🚿';
          $isActive = !$bottle->finished_at;
        @endphp
        <div class="overflow-hidden rounded-lg bg-white shadow-md">
          <div class="p-4">
            <div class="mb-3 flex items-start justify-between">
              <div class="flex-1">
                <div class="mb-1 flex items-center gap-2">
                  <span class="text-xl">{{ $icon }}</span>
                  <h3 class="font-bold">{{ ucfirst($bottle->location) }}</h3>
                </div>
                <p class="text-sm text-gray-500">
                  Instalada: {{ $bottle->installed_at->format('d/m/Y') }}
                </p>
              </div>
              <span
                class="{{ $isActive ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} rounded-full px-3 py-1 text-xs font-medium"
              >
                {{ $isActive ? '🟢 Activa' : '⚪ Terminada' }}
              </span>
            </div>

            <div class="mb-3 grid grid-cols-2 gap-3">
              @if ($bottle->finished_at)
                <div class="rounded bg-blue-50 p-2">
                  <div class="text-xs text-gray-600">Duración</div>
                  <div class="font-bold text-blue-600">{{ $bottle->duration_days }} días</div>
                </div>
                <div class="rounded bg-green-50 p-2">
                  <div class="text-xs text-gray-600">Uso diario</div>
                  <div class="font-bold text-green-600">
                    {{ number_format($bottle->estimated_daily_usage, 3) }} kg</div>
                </div>
              @else
                <div class="rounded bg-yellow-50 p-2">
                  <div class="text-xs text-gray-600">Días activa</div>
                  <div class="font-bold text-yellow-600">{{ $bottle->days_elapsed }}</div>
                </div>
                <div class="rounded bg-purple-50 p-2">
                  <div class="text-xs text-gray-600">Peso</div>
                  <div class="font-bold text-purple-600">{{ $bottle->weight_kg }} kg</div>
                </div>
              @endif
            </div>

            @if ($bottle->purchase)
              <div class="flex items-center justify-between border-t pt-3 text-sm">
                <span class="text-gray-600">💰 Precio:</span>
                <span
                  class="font-bold text-blue-600">{{ number_format($bottle->purchase->price, 2) }}€</span>
              </div>
            @endif

            @if ($bottle->notes)
              <div class="mt-3 border-t pt-3">
                <p class="text-sm text-gray-600">{{ $bottle->notes }}</p>
              </div>
            @endif

            <!-- Acciones -->
            <div class="mt-3 flex gap-2 border-t pt-3">
              @if ($isActive)
                <button
                  wire:click="$dispatch('mark-finished', { bottleId: {{ $bottle->id }} })"
                  class="flex-1 rounded bg-orange-100 px-4 py-2 text-sm font-medium text-orange-700 transition hover:bg-orange-200"
                >
                  ⏹️ Marcar terminada
                </button>
              @endif
              <button
                wire:click="deleteBottle({{ $bottle->id }})"
                wire:confirm="¿Eliminar esta botella del historial?"
                class="rounded bg-red-100 px-4 py-2 text-sm font-medium text-red-700 transition hover:bg-red-200"
              >
                🗑️
              </button>
            </div>
          </div>
        </div>
      @empty
        <div class="rounded-lg bg-white p-8 text-center shadow-md">
          <p class="text-gray-500">No hay botellas registradas</p>
          <a
            href="{{ route('gas.install') }}"
            class="mt-2 inline-block text-blue-600 hover:underline"
          >
            Instalar la primera botella →
          </a>
        </div>
      @endforelse
    </div>

    <!-- Paginación -->
    <div class="mt-6">
      {{ $bottles->links() }}
    </div>
  </div>

  <!-- Modal para marcar como terminada -->
  <livewire:gas.mark-bottle-finished />
</div>
