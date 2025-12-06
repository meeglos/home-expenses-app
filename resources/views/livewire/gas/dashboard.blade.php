<div class="min-h-screen bg-gray-50">
  <!-- Header con navegación -->
  <div
    class="bg-linear-to-r from-pocket-red-500 to-pocket-red-600 sticky top-0 z-10 text-white shadow-lg"
  >
    <div class="mx-auto max-w-7xl px-4 py-4">
      <h1 class="text-2xl font-bold">⛽ Control de Gas</h1>
    </div>

    <!-- Tabs -->
    <div class="no-scrollbar flex overflow-x-auto">
      <button
        wire:click="$set('activeTab', 'overview')"
        class="{{ $activeTab === 'overview' ? 'border-white bg-pocket-red-700' : 'border-transparent hover:bg-pocket-red-600' }} min-w-fit flex-1 border-b-2 px-4 py-3 text-sm font-medium transition"
      >
        📊 Resumen
      </button>
      <button
        wire:click="$set('activeTab', 'active')"
        class="{{ $activeTab === 'active' ? 'border-white bg-pocket-red-700' : 'border-transparent hover:bg-pocket-red-600' }} min-w-fit flex-1 border-b-2 px-4 py-3 text-sm font-medium transition"
      >
        🔥 Activas
      </button>
      <button
        wire:click="$set('activeTab', 'stats')"
        class="{{ $activeTab === 'stats' ? 'border-white bg-pocket-red-700' : 'border-transparent hover:bg-pocket-red-600' }} min-w-fit flex-1 border-b-2 px-4 py-3 text-sm font-medium transition"
      >
        📈 Estadísticas
      </button>
    </div>
  </div>

  <div class="mx-auto max-w-7xl space-y-4 px-4 py-6">
    <!-- Botones de acción -->
    <div class="grid grid-cols-2 gap-3">
      <a
        href="{{ route('gas.install') }}"
        class="bg-pocket-red-500 hover:bg-pocket-red-600 flex transform items-center justify-center space-x-2 rounded-lg px-4 py-4 text-center font-bold text-white shadow-lg transition active:scale-95"
      >
        <span>➕</span>
        <span>Instalar Botella</span>
      </a>
      <button
        wire:click="$dispatch('openMoveModal')"
        class="bg-pocket-red-500 hover:bg-pocket-red-600 flex transform items-center justify-center space-x-2 rounded-lg px-4 py-4 text-center font-bold text-white shadow-lg transition active:scale-95"
      >
        <span>🔄</span>
        <span>Mover Botella</span>
      </button>
    </div>

    @if (session()->has('success'))
      <div
        class="border-pocket-red-500 bg-pocket-red-100 text-pocket-red-700 rounded border-l-4 p-4"
      >
        {{ session('success') }}
      </div>
    @endif

    <!-- Tab: Resumen -->
    @if ($activeTab === 'overview')
      <div class="grid grid-cols-2 gap-4">
        <div class="rounded-lg bg-white p-4 shadow">
          <div class="text-pocket-gray-500 text-sm">Total Gastado</div>
          <div class="text-pocket-red-500 text-2xl font-bold">
            {{ number_format($this->statistics['total_spent'], 2) }}€</div>
        </div>
        <div class="rounded-lg bg-white p-4 shadow">
          <div class="text-pocket-gray-500 text-sm">Botellas Usadas</div>
          <div class="text-pocket-teal-600 text-2xl font-bold">
            {{ $this->statistics['total_bottles'] }}
          </div>
        </div>
      </div>

      <!-- Resumen por ubicación -->
      <div class="space-y-4">
        @foreach (['cocina' => '🍳', 'calentador' => '🚿'] as $location => $icon)
          @php $stats = $this->statistics[$location]; @endphp
          <div class="overflow-hidden rounded-lg bg-white shadow-md">
            <div class="bg-linear-to-r border-b from-gray-100 to-gray-200 px-4 py-3">
              <h3 class="text-lg font-bold">{{ $icon }} {{ ucfirst($location) }}</h3>
            </div>
            <div class="space-y-3 p-4">
              @if ($stats['total_bottles'] > 0)
                <div class="flex justify-between">
                  <span class="text-pocket-gray-500">Duración promedio:</span>
                  <span class="font-bold">{{ $stats['avg_duration'] }} días</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-pocket-gray-500">Uso diario promedio:</span>
                  <span class="font-bold">{{ $stats['avg_daily_usage'] }} kg/día</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-pocket-gray-500">Rango duración:</span>
                  <span>{{ $stats['min_duration'] }}-{{ $stats['max_duration'] }} días</span>
                </div>

                @if ($stats['current_active'])
                  @php
                    $bottle = $stats['current_active'];
                    $percentage = $bottle->estimated_usage_percentage ?? 50;
                  @endphp
                  <div class="mt-4 border-t pt-3">
                    <div class="mb-2 flex justify-between text-sm">
                      <span class="text-pocket-teal-600 font-medium">✓ Activa</span>
                      <span class="text-pocket-gray-500">{{ $bottle->days_elapsed }} días</span>
                    </div>
                    <div class="bg-pocket-light-gray-200 h-3 w-full rounded-full">
                      <div
                        class="bg-linear-to-r h-3 rounded-full from-green-400 to-yellow-500 transition-all"
                        style="width: {{ min($percentage, 100) }}%"
                      ></div>
                    </div>
                    @if ($percentage)
                      <div class="text-pocket-gray-500 mt-1 text-right text-xs">
                        ~{{ round($percentage) }}%
                        estimado</div>
                    @endif
                  </div>
                @else
                  <div class="text-pocket-gray-500 mt-3 text-center text-sm">
                    Sin botella activa
                  </div>
                @endif
              @else
                <div class="text-pocket-gray-500 py-4 text-center">
                  Sin datos aún
                </div>
              @endif
            </div>
          </div>
        @endforeach
      </div>

      <!-- Últimas compras -->
      @if ($this->statistics['recent_purchases']->count() > 0)
        <div class="overflow-hidden rounded-lg bg-white shadow-md">
          <div
            class="bg-linear-to-r flex items-center justify-between border-b from-gray-100 to-gray-200 px-4 py-3"
          >
            <h3 class="text-lg font-bold">💶 Últimas Compras</h3>
            <a
              href="{{ route('gas.purchases') }}"
              class="text-pocket-teal-600 text-sm hover:underline"
            >Ver todo</a>
          </div>
          <div class="divide-y">
            @foreach ($this->statistics['recent_purchases'] as $purchase)
              <div class="flex items-center justify-between px-4 py-3">
                <div>
                  <div class="font-medium">{{ number_format($purchase->price, 2) }}€</div>
                  <div class="text-pocket-gray-500 text-sm">
                    {{ $purchase->purchase_date->format('d/m/Y') }}</div>
                </div>
                @if ($purchase->supplier)
                  <div class="text-pocket-gray-500 text-sm">{{ $purchase->supplier }}</div>
                @endif
              </div>
            @endforeach
          </div>
        </div>
      @endif
    @endif

    <!-- Tab: Botellas Activas -->
    @if ($activeTab === 'active')
      <div class="space-y-4">
        @forelse ($this->activeBottles as $bottle)
          @php
            $percentage = $bottle->estimated_usage_percentage ?? null;
            $icon = $bottle->location === 'cocina' ? '🍳' : '🚿';
          @endphp
          <div class="overflow-hidden rounded-lg bg-white shadow-md">
            <div class="p-4">
              <div class="mb-3 flex items-start justify-between">
                <div>
                  <h3 class="text-lg font-bold">{{ $icon }}
                    {{ ucfirst($bottle->location) }}</h3>
                  <p class="text-pocket-gray-500 text-sm">Instalada:
                    {{ $bottle->installed_at->format('d/m/Y H:i') }}</p>
                </div>
                <span
                  class="bg-pocket-teal-100 text-pocket-teal-800 rounded-full px-3 py-1 text-xs font-medium"
                >
                  Activa
                </span>
              </div>

              <div class="mb-4 space-y-2">
                <div class="flex justify-between text-sm">
                  <span class="text-pocket-gray-500">Días transcurridos:</span>
                  <span class="font-bold">{{ $bottle->days_elapsed }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-pocket-gray-500">Peso:</span>
                  <span>{{ $bottle->weight_kg }} kg</span>
                </div>
              </div>

              @if ($percentage)
                <div>
                  <div class="text-pocket-gray-500 mb-1 flex justify-between text-xs">
                    <span>Estimación de uso</span>
                    <span>{{ round($percentage) }}%</span>
                  </div>
                  <div class="bg-pocket-light-gray-200 h-3 w-full rounded-full">
                    <div
                      class="{{ $percentage > 80 ? 'bg-red-500' : 'bg-linear-to-r from-green-400 to-yellow-500' }} h-3 rounded-full transition-all"
                      style="width: {{ min($percentage, 100) }}%"
                    ></div>
                  </div>
                </div>
              @endif

              @if ($bottle->moves()->count() > 0)
                <div class="mt-3 border-t pt-3">
                  <div class="text-pocket-gray-500 mb-1 text-xs font-medium">📍 Historial de
                    movimientos:
                  </div>
                  <div class="space-y-1">
                    @foreach ($bottle->moves()->latest()->take(3)->get() as $move)
                      <div class="text-pocket-gray-500 flex items-center justify-between text-xs">
                        <span>
                          {{ ucfirst($move->from_location) }} → {{ ucfirst($move->to_location) }}
                        </span>
                        <span
                          class="text-pocket-gray-400">{{ $move->moved_at->format('d/m/Y') }}</span>
                      </div>
                    @endforeach
                  </div>
                </div>
              @endif

              @if ($bottle->purchase)
                <div class="mt-3 flex justify-between border-t pt-3 text-sm">
                  <span class="text-pocket-gray-500">Precio pagado:</span>
                  <span
                    class="text-pocket-teal-600 font-medium">{{ number_format($bottle->purchase->price, 2) }}€</span>
                </div>
              @endif
            </div>
          </div>
        @empty
          <div class="rounded-lg border border-yellow-200 bg-yellow-50 p-6 text-center">
            <p class="text-pocket-gray-500">No hay botellas activas actualmente</p>
          </div>
        @endforelse
      </div>

      <div class="text-center">
        <a
          href="{{ route('gas.history') }}"
          class="text-pocket-teal-600 hover:underline"
        >
          >
          Ver historial completo →
        </a>
      </div>
    @endif

    <!-- Tab: Estadísticas -->
    @if ($activeTab === 'stats')
      <div class="space-y-4">
        <!-- Comparación entre ubicaciones -->
        <div class="rounded-lg bg-white p-4 shadow-md">
          <h3 class="mb-4 text-lg font-bold">📊 Comparación de Consumo</h3>
          <div class="space-y-4">
            @foreach (['cocina' => '🍳', 'calentador' => '🚿'] as $location => $icon)
              @php $stats = $this->statistics[$location]; @endphp
              @if ($stats['total_bottles'] > 0)
                <div>
                  <div class="mb-2 flex items-center justify-between">
                    <span class="font-medium">{{ $icon }} {{ ucfirst($location) }}</span>
                    <span class="text-pocket-gray-500 text-sm">{{ $stats['total_bottles'] }}
                      botellas</span>
                  </div>
                  <div class="grid grid-cols-2 gap-2 text-sm">
                    <div class="bg-pocket-teal-50 rounded p-2">
                      <div class="text-pocket-gray-500 text-xs">Duración media</div>
                      <div class="text-pocket-teal-600 font-bold">{{ $stats['avg_duration'] }}d
                      </div>
                    </div>
                    <div class="bg-pocket-teal-50 rounded p-2">
                      <div class="text-pocket-gray-500 text-xs">Uso diario</div>
                      <div class="text-pocket-teal-600 font-bold">
                        {{ $stats['avg_daily_usage'] }}kg
                      </div>
                    </div>
                  </div>
                </div>
              @endif
            @endforeach
          </div>
        </div>

        <!-- Enlaces a más estadísticas -->
        <a
          href="{{ route('gas.purchases') }}"
          class="block rounded-lg bg-white p-4 shadow-md transition hover:bg-gray-50"
        >
          <div class="flex items-center justify-between">
            <div>
              <h4 class="font-bold">💰 Análisis de Precios</h4>
              <p class="text-pocket-gray-500 text-sm">Evolución y comparativas</p>vas</p>
            </div>
            <span class="text-pocket-teal-600">→</span>
          </div>
        </a>

        <a
          href="{{ route('gas.history') }}"
          class="block rounded-lg bg-white p-4 shadow-md transition hover:bg-gray-50"
        >
          <div class="flex items-center justify-between">
            <div>
              <h4 class="font-bold">📋 Historial Completo</h4>
              <p class="text-pocket-gray-500 text-sm">Todas las botellas registradas</p>
            </div>
            <span class="text-pocket-teal-600">→</span>
          </div>
        </a>
      </div>
    @endif
  </div>

  <!-- Modal para mover botellas -->
  <livewire:gas.move-bottle />
</div>
