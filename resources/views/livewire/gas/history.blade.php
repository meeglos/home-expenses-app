<div class="min-h-screen bg-gray-50">
  <!-- Header -->
  <div class="bg-linear-to-r sticky top-0 z-10 from-purple-600 to-purple-700 text-white shadow-lg">
    <div class="mx-auto max-w-7xl px-4 py-3">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
          <a
            href="{{ route('gas.dashboard') }}"
            class=""
          >
            <svg
              class="h-5 w-5 md:h-6 md:w-6"
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
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
              />
            </svg>
            Historial
          </h1>
        </div>

        <!-- User Dropdown -->
        <x-dropdown
          align="right"
          width="48"
        >
          <x-slot name="trigger">
            <button
              class="focus:outline-hidden inline-flex items-center rounded-md border border-transparent bg-white/10 p-2 text-sm font-medium leading-4 text-white transition duration-150 ease-in-out hover:bg-white/20 md:px-4 md:py-2"
            >
              <!-- Icono hamburger solo en móvil -->
              <svg
                class="h-5 w-5 md:hidden!"
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
      <div class="flex border-b border-purple-200 bg-purple-600">
        <button
          wire:click="$set('activeTab', 'history')"
          class="{{ $activeTab === 'history' ? 'border-white bg-purple-700' : 'border-transparent hover:bg-purple-500' }} min-w-fit flex-1 border-b-2 px-4 py-3 text-sm font-medium text-white transition"
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
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
              />
            </svg>
            Historial
          </span>
        </button>
        <button
          wire:click="$set('activeTab', 'activity')"
          class="{{ $activeTab === 'activity' ? 'border-white bg-purple-700' : 'border-transparent hover:bg-purple-500' }} min-w-fit flex-1 border-b-2 px-4 py-3 text-sm font-medium text-white transition"
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
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
            Actividad
          </span>
        </button>
      </div>
    </div>
  </div>

  <div class="mx-auto max-w-7xl px-4 py-6">
    @if (session()->has('success'))
      <div
        class="border-pocket-red-500 bg-pocket-red-100 text-pocket-red-700 mb-4 rounded border-l-4 p-4"
      >
        <div class="flex items-center gap-2">
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
              d="M5 13l4 4L19 7"
            />
          </svg>
          {{ session('success') }}
        </div>
      </div>
    @endif

    @if ($activeTab === 'history')
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
              <option value="cocina">Cocina</option>
              <option value="calentador">Calentador</option>
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
            $isActive = !$bottle->finished_at;
          @endphp
          <div class="overflow-hidden rounded-lg bg-white shadow-md">
            <div class="p-4">
              <div class="mb-3 flex items-start justify-between">
                <div class="flex-1">
                  <div class="mb-1 flex items-center gap-2">
                    @if ($bottle->location === 'cocina')
                      <x-gameicon-gas-stove class="h-5 w-5" />
                    @else
                      <x-gmdi-gas-meter-o class="h-5 w-5" />
                    @endif
                    <h3 class="font-bold">{{ ucfirst($bottle->location) }}</h3>
                  </div>
                  <p class="text-sm text-gray-500">
                    Instalada: {{ $bottle->installed_at->format('d/m/Y') }}
                  </p>
                </div>
                <span
                  class="{{ $isActive ? 'bg-pocket-teal-100 text-pocket-teal-800' : 'bg-pocket-light-gray-100 text-pocket-gray-500' }} rounded-full px-3 py-1 text-xs font-medium"
                >
                  <span class="flex items-center gap-1">
                    @if ($isActive)
                      <svg
                        class="h-3 w-3"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                      >
                        <circle
                          cx="10"
                          cy="10"
                          r="6"
                        />
                      </svg>
                      Activa
                    @else
                      <svg
                        class="h-3 w-3"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 20 20"
                      >
                        <circle
                          cx="10"
                          cy="10"
                          r="6"
                          stroke-width="2"
                        />
                      </svg>
                      Terminada
                    @endif
                  </span>
                </span>
              </div>

              <div class="mb-3 grid grid-cols-2 gap-3">
                @if ($bottle->finished_at)
                  <div class="bg-pocket-teal-50 rounded p-2">
                    <div class="text-pocket-gray-500 text-xs">Duración</div>
                    <div class="text-pocket-teal-600 font-bold">{{ $bottle->duration_days }} días
                    </div>
                  </div>
                  <div class="bg-pocket-teal-50 rounded p-2">
                    <div class="text-pocket-gray-500 text-xs">Uso diario</div>
                    <div class="text-pocket-teal-600 font-bold">
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
                  <span class="flex items-center gap-1 text-gray-600">
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
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                      />
                    </svg>
                    Precio:
                  </span>
                  <span
                    class="text-pocket-teal-600 font-bold">{{ number_format($bottle->purchase->price, 2) }}€</span>
                </div>
              @endif

              @if ($bottle->moves->count() > 0)
                <div class="mt-3 border-t pt-3">
                  <div class="mb-2 flex items-center gap-2">
                    <span class="flex items-center gap-1 text-sm font-medium text-gray-700">
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
                          d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"
                        />
                      </svg>
                      Movimientos
                    </span>
                    <span
                      class="bg-pocket-teal-100 text-pocket-teal-700 rounded-full px-2 py-0.5 text-xs font-medium"
                    >{{ $bottle->moves->count() }}</span>
                  </div>
                  <div class="space-y-2">
                    @foreach ($bottle->moves as $move)
                      <div class="rounded-lg bg-gray-50 p-3">
                        <div class="mb-1 flex items-center justify-between">
                          <span class="flex items-center gap-1 text-sm font-medium text-gray-700">
                            {{ ucfirst($move->from_location) }}
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
                            {{ ucfirst($move->to_location) }}
                          </span>
                          <span class="text-xs text-gray-500">
                            {{ $move->moved_at->format('d/m/Y H:i') }}
                          </span>
                        </div>
                        @if ($move->reason)
                          <p class="flex items-center gap-1 text-xs text-gray-600">
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
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"
                              />
                            </svg>
                            {{ $move->reason }}
                          </p>
                        @endif
                      </div>
                    @endforeach
                  </div>
                </div>
              @endif

              @if ($bottle->notes)
                <div class="mt-3 border-t pt-3">
                  <p class="flex items-center gap-1 text-sm text-gray-600">
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
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                      />
                    </svg>
                    {{ $bottle->notes }}
                  </p>
                </div>
              @endif

              <!-- Acciones -->
              <div class="mt-3 flex gap-2 border-t pt-3">
                @if ($isActive)
                  <button
                    wire:click="$dispatch('mark-finished', { bottleId: {{ $bottle->id }} })"
                    class="flex flex-1 items-center justify-center gap-1 rounded bg-orange-100 px-4 py-2 text-sm font-medium text-orange-700 transition hover:bg-orange-200"
                  >
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
                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                      />
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"
                      />
                    </svg>
                    Marcar terminada
                  </button>
                @endif
                <button
                  wire:click="deleteBottle({{ $bottle->id }})"
                  wire:confirm="¿Eliminar esta botella del historial?"
                  class="bg-pocket-red-100 text-pocket-red-700 hover:bg-pocket-red-200 flex items-center justify-center rounded px-4 py-2 text-sm font-medium transition"
                >
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
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                    />
                  </svg>
                </button>
              </div>
            </div>
          </div>
        @empty
          <div class="rounded-lg bg-white p-8 text-center shadow-md">
            <p class="text-gray-500">No hay botellas registradas</p>
            <a
              href="{{ route('gas.install') }}"
              class="text-pocket-teal-600 mt-2 inline-block hover:underline"
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
    @endif

    @if ($activeTab === 'activity')
      <!-- Filtro por año -->
      <div class="mb-4 rounded-lg bg-white p-4 shadow-md">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-3">
          <label class="text-sm font-medium text-gray-700">Filtrar por año:</label>
          <select
            wire:model.live="activity_year_filter"
            class="w-full rounded-lg border border-gray-300 px-4 py-2 text-base focus:border-purple-500 focus:ring-2 focus:ring-purple-500 sm:w-auto"
          >
            <option value="">Todos los años</option>
            @foreach ($years as $year)
              <option value="{{ $year }}">{{ $year }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <!-- Timeline de actividad -->
      <div class="space-y-2">
        @forelse ($activities as $activity)
          <div class="rounded-lg bg-white p-4 shadow-md">
            <div class="flex items-start gap-3">
              <!-- Icono según tipo -->
              @if ($activity['type'] === 'call')
                <svg
                  class="h-6 w-6 shrink-0 text-green-600"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                  />
                </svg>
              @elseif ($activity['type'] === 'purchase')
                <svg
                  class="h-6 w-6 shrink-0 text-blue-600"
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
              @elseif ($activity['type'] === 'installation')
                <svg
                  class="h-6 w-6 shrink-0 text-indigo-600"
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
              @else
                <svg
                  class="h-6 w-6 shrink-0 text-orange-600"
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
              @endif

              <div class="flex-1">
                <div class="mb-1 flex items-center gap-2 text-sm text-gray-500">
                  <span class="font-medium">{{ $activity['date']->format('d/m/Y H:i') }}</span>
                  <span class="text-gray-400">•</span>
                  <span>{{ $activity['date']->diffForHumans() }}</span>
                </div>

                @if ($activity['type'] === 'call')
                  <p class="text-gray-900">
                    📞 Llamaste a
                    <span class="font-semibold">{{ $activity['data']->supplier->name }}</span>
                    <span class="text-gray-500">({{ $activity['data']->supplier->phone }})</span>
                  </p>
                @elseif ($activity['type'] === 'purchase')
                  <p class="text-gray-900">
                    💰 Registraste compra de
                    <span class="font-semibold">1 botella</span>
                    por
                    <span
                      class="text-pocket-teal-600 font-semibold">{{ number_format($activity['data']->price, 2) }}€</span>
                    @if ($activity['data']->supplier)
                      de
                      <span class="font-semibold">{{ $activity['data']->supplier->name }}</span>
                    @endif
                  </p>
                @elseif ($activity['type'] === 'installation')
                  <p class="text-gray-900">
                    🔧 Instalaste botella en
                    <span class="font-semibold">{{ ucfirst($activity['data']->location) }}</span>
                    <span class="text-gray-500">({{ $activity['data']->weight_kg }} kg)</span>
                  </p>
                @else
                  <p class="text-gray-900">
                    🔄 Cambiaste botella de
                    <span
                      class="font-semibold">{{ ucfirst($activity['data']->from_location) }}</span>
                    a
                    <span
                      class="font-semibold">{{ ucfirst($activity['data']->to_location) }}</span>
                  </p>
                @endif
              </div>
            </div>
          </div>
        @empty
          <div class="rounded-lg bg-white p-8 text-center shadow-md">
            <svg
              class="mx-auto h-12 w-12 text-gray-400"
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
            <p class="mt-2 text-gray-500">No hay actividad registrada</p>
          </div>
        @endforelse
      </div>
    @endif
  </div>

  <!-- Modal para marcar como terminada -->
  <livewire:gas.mark-bottle-finished />
</div>
