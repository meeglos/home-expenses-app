<div class="min-h-screen bg-gray-50">
  <!-- Header -->
  <div class="bg-linear-to-r sticky top-0 z-10 from-indigo-600 to-indigo-700 text-white shadow-lg">
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
      <h1 class="text-2xl font-bold">💶 Compras de Gas</h1>
    </div>
  </div>

  <div class="mx-auto max-w-7xl space-y-4 px-4 py-6">
    <!-- Estadísticas rápidas -->
    <div class="grid grid-cols-2 gap-3">
      <div class="rounded-lg bg-white p-4 shadow">
        <div class="mb-1 text-xs text-gray-500">Total Gastado</div>
        <div class="text-pocket-red-500 text-xl font-bold">{{ number_format($stats['total'], 2) }}€
        </div>
      </div>
      <div class="rounded-lg bg-white p-4 shadow">
        <div class="mb-1 text-xs text-gray-500">Precio Medio</div>
        <div class="text-pocket-teal-600 text-xl font-bold">
          {{ number_format($stats['avg_price'], 2) }}€
        </div>
      </div>
    </div>

    <div class="bg-linear-to-r rounded-lg from-indigo-50 to-purple-50 p-4 shadow">
      <div class="flex items-center justify-between">
        <div>
          <div class="text-sm text-gray-600">Este Año</div>
          <div class="text-pocket-teal-600 text-2xl font-bold">
            {{ number_format($stats['this_year'], 2) }}€</div>
        </div>
        @if ($stats['last_purchase'])
          <div class="text-right">
            <div class="text-sm text-gray-600">Última Compra</div>
            <div class="font-bold">{{ number_format($stats['last_purchase']->price, 2) }}€</div>
            <div class="text-xs text-gray-500">
              {{ $stats['last_purchase']->purchase_date->format('d/m/Y') }}</div>
          </div>
        @endif
      </div>
    </div>

    <!-- Evolución de precios -->
    @if ($priceEvolution->count() > 0)
      <div class="overflow-hidden rounded-lg bg-white shadow-md">
        <div class="bg-linear-to-r border-b from-gray-100 to-gray-200 px-4 py-3">
          <h3 class="text-lg font-bold">📈 Evolución de Precios (últimos 12 meses)</h3>
        </div>
        <div class="p-4">
          <div class="space-y-2">
            @foreach ($priceEvolution as $month)
              @php
                $maxPrice = $priceEvolution->max('max_price');
                $barWidth = ($month->avg_price / $maxPrice) * 100;
              @endphp
              <div>
                <div class="mb-1 flex justify-between text-sm">
                  <span
                    class="font-medium">{{ \Carbon\Carbon::parse($month->month)->format('M Y') }}</span>
                  <span class="text-gray-600">{{ number_format($month->avg_price, 2) }}€
                    ({{ $month->count }} compras)
                  </span>
                </div>
                <div class="h-2 w-full rounded-full bg-gray-200">
                  <div
                    class="bg-linear-to-r h-2 rounded-full from-blue-400 to-indigo-500"
                    style="width: {{ $barWidth }}%"
                  ></div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    @endif

    <!-- Filtros -->
    <div class="rounded-lg bg-white p-4 shadow-md">
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="mb-1 block text-sm font-medium text-gray-700">Año</label>
          <select
            wire:model.live="year_filter"
            class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-indigo-500"
          >
            <option value="">Todos</option>
            @foreach ($years as $year)
              <option value="{{ $year }}">{{ $year }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="mb-1 block text-sm font-medium text-gray-700">Proveedor</label>
          <select
            wire:model.live="supplier_filter"
            class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-indigo-500"
          >
            <option value="">Todos</option>
            @foreach ($suppliers as $supplier)
              <option value="{{ $supplier }}">{{ $supplier }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>

    <!-- Lista de compras -->
    <div class="space-y-3">
      @forelse ($purchases as $purchase)
        <div class="overflow-hidden rounded-lg bg-white shadow-md">
          <div class="p-4">
            <div class="mb-3 flex items-start justify-between">
              <div class="flex-1">
                <div class="text-pocket-teal-600 text-2xl font-bold">
                  {{ number_format($purchase->price, 2) }}€</div>
                <p class="text-sm text-gray-500">{{ $purchase->purchase_date->format('d/m/Y') }}
                </p>
              </div>
              <div class="text-right">
                <div class="text-sm text-gray-600">{{ $purchase->weight_kg }} kg</div>
                <div class="text-xs text-gray-500">
                  {{ number_format($purchase->price_per_kg, 2) }}€/kg</div>
              </div>
            </div>

            @if ($purchase->supplier)
              <div class="mb-2">
                <span class="rounded bg-gray-100 px-2 py-1 text-xs font-medium text-gray-800">
                  🏪 {{ $purchase->supplier }}
                </span>
              </div>
            @endif

            @if ($purchase->gasBottle)
              <div class="flex items-center gap-1.5 border-t pt-2 text-sm text-gray-600">
                @if ($purchase->gasBottle->location === 'cocina')
                  <x-gameicon-gas-stove class="h-4 w-4" />
                @else
                  <x-gmdi-gas-meter-o class="h-4 w-4" />
                @endif
                {{ ucfirst($purchase->gasBottle->location) }}
                @if ($purchase->gasBottle->duration_days)
                  <span class="ml-2">• Duró {{ $purchase->gasBottle->duration_days }} días</span>
                @endif
              </div>
            @endif

            @if ($purchase->notes)
              <div class="mt-2 border-t pt-2 text-sm text-gray-600">
                {{ $purchase->notes }}
              </div>
            @endif
          </div>
        </div>
      @empty
        <div class="rounded-lg bg-white p-8 text-center shadow-md">
          <p class="text-gray-500">No hay compras registradas</p>
        </div>
      @endforelse
    </div>

    <!-- Paginación -->
    <div class="mt-6">
      {{ $purchases->links() }}
    </div>
  </div>
</div>
