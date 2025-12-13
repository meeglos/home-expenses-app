<div class="min-h-screen bg-gray-50">
  <!-- Header con navegación -->
  <div
    class="text-pocket-dark-900 border-pocket-light-gray-200 sticky top-0 z-10 border-b bg-white shadow-lg"
  >
    <div class="mx-auto max-w-7xl px-4 py-3">
      <div class="flex items-center justify-between">
        <h1 class="flex items-center gap-1.5 text-base font-bold md:gap-2 md:text-xl">
          <svg
            class="h-4 w-4 md:h-5 md:w-5"
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
              <svg
                class="md:hidden! h-5 w-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16"
                />
              </svg>

              <!-- Nombre y chevron solo en desktop -->
              <span class="hidden! md:flex! md:items-center md:gap-1">
                <span>{{ Auth::user()->name }}</span>
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
            <svg
              class="h-4 w-4"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
              />
            </svg>
            Resumen
          </span>
        </button>
        <button
          wire:click="$set('activeTab', 'active')"
          class="{{ $activeTab === 'active' ? 'bg-pocket-yellow-500 text-white' : 'bg-white text-pocket-dark-600 hover:bg-pocket-light-gray-100 border-b-2 border-pocket-yellow-500' }} min-w-fit flex-1 rounded-t-lg px-4 py-3 text-sm font-medium transition"
        >
          <span class="flex items-center justify-center gap-1.5">
            <svg
              class="h-4 w-4"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M13 10V3L4 14h7v7l9-11h-7z"
              />
            </svg>
            Activas
          </span>
        </button>
        <button
          wire:click="$set('activeTab', 'stats')"
          class="{{ $activeTab === 'stats' ? 'bg-pocket-red-500 text-white' : 'bg-white text-pocket-dark-600 hover:bg-pocket-light-gray-100 border-b-2 border-pocket-red-500' }} min-w-fit flex-1 rounded-t-lg px-4 py-3 text-sm font-medium transition"
        >
          <span class="flex items-center justify-center gap-1.5">
            <svg
              class="h-4 w-4"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"
              />
            </svg>
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
            d="M12 6v6m0 0v6m0-6h6m-6 0H6"
          />
        </svg>
        <span>Instalar</span>
      </a>
      <button
        wire:click="$dispatch('openMoveModal')"
        class="hover:bg-pocket-yellow-50 border-pocket-yellow-500 text-pocket-yellow-600 flex min-w-[100px] flex-1 transform items-center justify-center gap-2 rounded-lg border-2 bg-white px-4 py-4 text-center font-bold shadow-md transition active:scale-95"
      >
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
            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"
          />
        </svg>
        <span>Mover</span>
      </button>
      <a
        href="{{ route('gas.purchases') }}"
        class="hover:bg-pocket-red-50 border-pocket-red-500 text-pocket-red-600 flex min-w-[100px] flex-1 transform items-center justify-center gap-2 rounded-lg border-2 bg-white px-4 py-4 text-center font-bold shadow-md transition active:scale-95"
      >
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
            <div class="bg-linear-to-r border-b from-gray-100 to-gray-200 px-4 py-3">
              <h3 class="flex items-center gap-2 text-lg font-bold">
                @if ($location === 'cocina')
                  <x-gameicon-gas-stove class="h-5 w-5" />
                @else
                  <x-gmdi-gas-meter-o class="h-5 w-5" />
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
                        <svg
                          class="h-4 w-4"
                          fill="none"
                          stroke="currentColor"
                          viewBox="0 0 24 24"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7"
                          />
                        </svg>
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
          <div
            class="bg-linear-to-r flex items-center justify-between border-b from-gray-100 to-gray-200 px-4 py-3"
          >
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
              Últimas Compras
            </h3>
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
                      <x-gameicon-gas-stove class="h-5 w-5" />
                    @else
                      <x-gmdi-gas-meter-o class="h-5 w-5" />
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
                    class="text-pocket-gray-500 mb-1 flex items-center gap-1 text-xs font-medium"
                  >
                    <svg
                      class="h-3 w-3"
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
                    Historial de movimientos:
                  </div>
                  <div class="space-y-1">
                    @foreach ($bottle->moves()->latest()->take(3)->get() as $move)
                      <div class="text-pocket-gray-500 flex items-center justify-between text-xs">
                        <span>
                          {{ ucfirst($move->from_location) }}
                          <svg
                            class="h-3 w-3"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                          >
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M14 5l7 7m0 0l-7 7m7-7H3"
                            />
                          </svg>
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
            <svg
              class="h-4 w-4"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M14 5l7 7m0 0l-7 7m7-7H3"
              />
            </svg>
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
                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
              />
            </svg>
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
                        <x-gameicon-gas-stove class="h-4 w-4" />
                      @else
                        <x-gmdi-gas-meter-o class="h-4 w-4" />
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
                Análisis de Precios
              </h4>
              <p class="text-pocket-gray-500 text-sm">Evolución y comparativas</p>vas</p>
            </div>
            <svg
              class="text-pocket-teal-600 h-5 w-5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M14 5l7 7m0 0l-7 7m7-7H3"
              />
            </svg>
          </div>
        </a>

        <a
          href="{{ route('gas.history') }}"
          class="block rounded-lg bg-white p-4 shadow-md transition hover:bg-gray-50"
        >
          <div class="flex items-center justify-between">
            <div>
              <h4 class="flex items-center gap-2 font-bold">
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
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                  />
                </svg>
                Historial Completo
              </h4>
              <p class="text-pocket-gray-500 text-sm">Todas las botellas registradas</p>
            </div>
            <svg
              class="text-pocket-teal-600 h-5 w-5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M14 5l7 7m0 0l-7 7m7-7H3"
              />
            </svg>
          </div>
        </a>

        <!-- Actividad -->
        <a
          href="{{ route('gas.history') }}?tab=activity"
          class="flex items-center justify-between rounded-lg border border-gray-200 bg-white p-4 transition hover:border-indigo-500 hover:shadow-md"
        >
          <div class="flex items-center gap-3">
            <svg
              class="text-pocket-teal-600 h-8 w-8"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
            <div>
              <h4 class="font-bold">📋 Actividad</h4>
              <p class="text-pocket-gray-500 text-sm">Todos los movimientos y llamadas</p>
            </div>
          </div>
          <svg
            class="text-pocket-teal-600 h-5 w-5"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M14 5l7 7m0 0l-7 7m7-7H3"
            />
          </svg>
        </a>
      </div>
    @endif
  </div>

  <!-- Modal para mover botellas -->
  <livewire:gas.move-bottle />
</div>
