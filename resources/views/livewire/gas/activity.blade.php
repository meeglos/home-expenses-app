<div class="min-h-screen bg-gray-50">
  <!-- Header -->
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
              <x-icons.menu class="md:hidden! h-5 w-5" />

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
        <a
          href="{{ route('gas.dashboard') }}"
          class="text-pocket-dark-600 hover:bg-pocket-light-gray-100 border-pocket-teal-500 min-w-fit flex-1 rounded-t-lg border-b-2 bg-white px-4 py-3 text-sm font-medium transition"
        >
          <span class="flex items-center justify-center gap-1.5">
            <x-icons.chart-bar class="h-4 w-4" />
            Resumen
          </span>
        </a>
        <a
          href="{{ route('gas.dashboard') }}?tab=active"
          class="text-pocket-dark-600 hover:bg-pocket-light-gray-100 border-pocket-yellow-500 min-w-fit flex-1 rounded-t-lg border-b-2 bg-white px-4 py-3 text-sm font-medium transition"
        >
          <span class="flex items-center justify-center gap-1.5">
            <x-icons.lightning class="h-4 w-4" />
            Activas
          </span>
        </a>
        <a
          href="{{ route('gas.dashboard') }}?tab=stats"
          class="bg-pocket-red-500 min-w-fit flex-1 rounded-t-lg px-4 py-3 text-sm font-medium text-white transition"
        >
          <span class="flex items-center justify-center gap-1.5">
            <x-icons.presentation-chart class="h-4 w-4" />
            Estadísticas
          </span>
        </a>
      </div>
    </div>
  </div>

  <div class="mx-auto max-w-7xl space-y-4 px-4 py-6">
    <!-- Filtro por año -->
    <div class="rounded-lg bg-white p-4 shadow-md">
      <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-3">
        <label class="text-sm font-medium text-gray-700">Filtrar por año:</label>
        <select
          wire:model.live="year_filter"
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
                  <span class="font-semibold">{{ ucfirst($activity['data']->to_location) }}</span>
                </p>
              @endif
            </div>
          </div>
        </div>
      @empty
        <div class="rounded-lg bg-white p-8 text-center shadow-md">
          <x-icons.clock class="mx-auto h-12 w-12 text-gray-400" />
          <p class="mt-2 text-gray-500">No hay actividad registrada</p>
        </div>
      @endforelse
    </div>
  </div>
</div>
