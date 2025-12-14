<div class="min-h-screen bg-gray-50">
  <!-- Header con navegación -->
  <div
    class="text-pocket-dark-900 border-pocket-light-gray-200 sticky top-0 z-10 border-b bg-white shadow-lg"
  >
    <div class="mx-auto max-w-7xl px-4 py-3">
      <div class="flex items-center justify-between">
        <h1 class="flex items-center gap-1.5 text-base font-bold md:gap-2 md:text-xl">
          <x-icons.lightbulb class="h-4 w-4 md:h-5 md:w-5" />
          Control de Gas
        </h1>

        <!-- User Dropdown -->
        <x-dropdown
          align="right"
          width="48"
        >
          <x-slot name="trigger">
            <button
              class="focus:outline-hidden border-pocket-light-gray-300 text-pocket-dark-900 hover:bg-pocket-light-gray-100 inline-flex items-center rounded-md border bg-white p-2 text-sm font-medium leading-4 transition duration-150 ease-in-out md:px-4 md:py-2"
            >
              <!-- Icono hamburger solo en móvil -->
              <x-icons.menu class="md:hidden! h-5 w-5" />

              <!-- Nombre y chevron solo en desktop -->
              <span class="hidden! md:flex! md:items-center md:gap-1">
                <span>{{ Auth::user()->name }}</span>
                <x-icons.chevron-down />
              </span>
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

    <!-- Tabs -->
    <div class="mx-auto max-w-7xl">
      <div class="no-scrollbar flex overflow-x-auto">
        <button
          wire:click="$set('activeTab', 'overview')"
          class="{{ $activeTab === 'overview' ? 'bg-pocket-teal-500 text-white' : 'bg-white text-pocket-dark-600 hover:bg-pocket-light-gray-100 border-b-2 border-pocket-teal-500' }} min-w-fit flex-1 rounded-t-lg px-4 py-3 text-sm font-medium transition"
        >
          <span class="flex items-center justify-center gap-1.5">
            <x-icons.chart-bar class="h-4 w-4" />
            Resumen
          </span>
        </button>
        <button
          wire:click="$set('activeTab', 'active')"
          class="{{ $activeTab === 'active' ? 'bg-pocket-yellow-500 text-white' : 'bg-white text-pocket-dark-600 hover:bg-pocket-light-gray-100 border-b-2 border-pocket-yellow-500' }} min-w-fit flex-1 rounded-t-lg px-4 py-3 text-sm font-medium transition"
        >
          <span class="flex items-center justify-center gap-1.5">
            <x-icons.lightning class="h-4 w-4" />
            Activas
          </span>
        </button>
        <button
          wire:click="$set('activeTab', 'stats')"
          class="{{ $activeTab === 'stats' ? 'bg-pocket-red-500 text-white' : 'bg-white text-pocket-dark-600 hover:bg-pocket-light-gray-100 border-b-2 border-pocket-red-500' }} min-w-fit flex-1 rounded-t-lg px-4 py-3 text-sm font-medium transition"
        >
          <span class="flex items-center justify-center gap-1.5">
            <x-icons.presentation-chart class="h-4 w-4" />
            Estadísticas
          </span>
        </button>
      </div>
    </div>
  </div>

  <div class="mx-auto max-w-7xl space-y-4 px-4 py-6">
    <!-- Botones de acción -->
    <div class="flex flex-wrap gap-3 md:flex-nowrap">
      <a
        href="{{ route('gas.install') }}"
        class="hover:bg-pocket-teal-50 border-pocket-teal-500 text-pocket-teal-600 flex min-w-[100px] flex-1 transform items-center justify-center gap-2 rounded-lg border-2 bg-white px-4 py-4 text-center font-bold shadow-md transition active:scale-95"
      >
        <x-icons.plus />
        <span>Instalar</span>
      </a>
      <button
        wire:click="$dispatch('openMoveModal')"
        class="hover:bg-pocket-yellow-50 border-pocket-yellow-500 text-pocket-yellow-600 flex min-w-[100px] flex-1 transform items-center justify-center gap-2 rounded-lg border-2 bg-white px-4 py-4 text-center font-bold shadow-md transition active:scale-95"
      >
        <x-icons.arrows-exchange />
        <span>Mover</span>
      </button>
      <a
        href="{{ route('gas.purchases') }}"
        class="hover:bg-pocket-red-50 border-pocket-red-500 text-pocket-red-600 flex min-w-[100px] flex-1 transform items-center justify-center gap-2 rounded-lg border-2 bg-white px-4 py-4 text-center font-bold shadow-md transition active:scale-95"
      >
        <x-icons.currency-dollar />
        <span>Comprar</span>
      </a>
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
            <div
              class="{{ $location === 'cocina' ? 'bg-pocket-yellow-500' : 'bg-pocket-red-500' }} border-b px-4 py-3"
            >
              <h3 class="flex items-center gap-2 text-lg font-bold text-white">
                @if ($location === 'cocina')
                  <x-icons.fire />
                @else
                  <x-icons.calendar-plus />
                @endif
                {{ ucfirst($location) }}
              </h3>
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
                      <span class="text-pocket-teal-600 flex items-center gap-1 font-medium">
                        <x-icons.check />
                        Activa
                      </span>
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
          <div class="bg-pocket-teal-500 flex items-center justify-between border-b px-4 py-3">
            <h3 class="flex items-center gap-2 text-lg font-bold text-white">
              <x-icons.currency-euro />
              Últimas Compras
            </h3>
            <a
              href="{{ route('gas.purchases') }}"
              class="text-sm text-white hover:underline"
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
                  <div class="text-pocket-gray-500 text-sm">{{ $purchase->supplier->name }}</div>
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
          @endphp
          <div class="overflow-hidden rounded-lg bg-white shadow-md">
            <div class="p-4">
              <div class="mb-3 flex items-start justify-between">
                <div>
                  <h3 class="flex items-center gap-2 text-lg font-bold">
                    @if ($bottle->location === 'cocina')
                      <x-icons.fire />
                    @else
                      <x-icons.calendar-plus />
                    @endif
                    {{ ucfirst($bottle->location) }}
                  </h3>
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
                  <div
                    class="text-pocket-gray-500 mb-1 flex items-center gap-1 text-xs font-medium">
                    <x-icons.map-pin />
                    Historial de movimientos:
                  </div>
                  <div class="space-y-1">
                    @foreach ($bottle->moves()->latest()->take(3)->get() as $move)
                      <div class="text-pocket-gray-500 flex items-center justify-between text-xs">
                        <span class="flex items-center gap-1">
                          {{ ucfirst($move->from_location) }}
                          <x-icons.arrow-right class="h-3 w-3" />
                          {{ ucfirst($move->to_location) }}
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
          <span class="flex items-center gap-1">
            Ver historial completo
            <x-icons.arrow-right class="h-4 w-4" />
          </span>
        </a>
      </div>
    @endif

    <!-- Tab: Estadísticas -->
    @if ($activeTab === 'stats')
      <div class="space-y-4">
        <!-- Comparación entre ubicaciones -->
        <div class="rounded-lg bg-white p-4 shadow-md">
          <h3 class="mb-4 flex items-center gap-2 text-lg font-bold">
            <x-icons.chart-bar />
            Comparación de Consumo
          </h3>
          <div class="space-y-4">
            @foreach (['cocina', 'calentador'] as $location)
              @php $stats = $this->statistics[$location]; @endphp
              @if ($stats['total_bottles'] > 0)
                <div>
                  <div class="mb-2 flex items-center justify-between">
                    <span class="flex items-center gap-1.5 font-medium">
                      @if ($location === 'cocina')
                        <x-icons.fire class="h-4 w-4" />
                      @else
                        <x-icons.calendar-plus class="h-4 w-4" />
                      @endif
                      {{ ucfirst($location) }}
                    </span>
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
              <h4 class="flex items-center gap-2 font-bold">
                <x-icons.currency-dollar />
                Análisis de Precios
              </h4>
              <p class="text-pocket-gray-500 text-sm">Evolución y comparativas</p>
            </div>
            <x-icons.arrow-right class="text-pocket-teal-600 h-5 w-5" />
          </div>
        </a>

        <a
          href="{{ route('gas.history') }}"
          class="block rounded-lg bg-white p-4 shadow-md transition hover:bg-gray-50"
        >
          <div class="flex items-center justify-between">
            <div>
              <h4 class="flex items-center gap-2 font-bold">
                <x-icons.document />
                Historial Completo
              </h4>
              <p class="text-pocket-gray-500 text-sm">Todas las botellas registradas</p>
            </div>
            <x-icons.arrow-right class="text-pocket-teal-600 h-5 w-5" />
          </div>
        </a>

        <a
          href="{{ route('gas.activity') }}"
          class="block rounded-lg bg-white p-4 shadow-md transition hover:bg-gray-50"
        >
          <div class="flex items-center justify-between">
            <div>
              <h4 class="flex items-center gap-2 font-bold">
                <x-icons.clock />
                Actividad
              </h4>
              <p class="text-pocket-gray-500 text-sm">Todos los movimientos y llamadas</p>
            </div>
            <x-icons.arrow-right class="text-pocket-teal-600 h-5 w-5" />
          </div>
        </a>
      </div>
    @endif
  </div>

  <!-- Modal para mover botellas -->
  <livewire:gas.move-bottle />
</div>
