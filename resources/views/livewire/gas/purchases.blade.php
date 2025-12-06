<div class="min-h-screen bg-gray-50">
  <!-- Header -->
  <div class="bg-linear-to-r sticky top-0 z-10 from-indigo-600 to-indigo-700 text-white shadow-lg">
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
          <h1 class="text-base font-bold md:text-xl">💶 Compras de Gas</h1>
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
                class="h-5 w-5 md:!hidden"
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
              <span class="!hidden md:!flex md:items-center md:gap-1">
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
  </div>

  <div class="mx-auto max-w-7xl space-y-4 px-4 py-6">
    @if ($activeSuppliers->count() === 0)
      <!-- Mensaje cuando no hay proveedores -->
      <div class="rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 p-6 text-center">
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
            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
          />
        </svg>
        <p class="mt-2 text-sm text-gray-600">
          No has configurado proveedores
        </p>
        <a
          href="{{ route('gas.suppliers') }}"
          class="text-pocket-teal-600 hover:text-pocket-teal-700 mt-2 inline-block text-sm font-medium"
        >
          Configurar proveedores →
        </a>
      </div>
    @endif

    <!-- Formulario para registrar compra -->
    <div class="overflow-hidden rounded-lg bg-white shadow-md">
      <div class="bg-linear-to-r border-b from-indigo-100 to-purple-100 px-4 py-3">
        <h3 class="text-lg font-bold">💰 Registrar Nueva Compra</h3>
      </div>
      <form
        wire:submit="savePurchase"
        class="space-y-4 p-4"
      >
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
          <!-- Precio -->
          <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">
              Precio (€) <span class="text-red-500">*</span>
            </label>
            <input
              type="number"
              step="0.01"
              wire:model="price"
              class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-indigo-500"
              placeholder="15.50"
              required
            >
            @error('price')
              <span class="text-sm text-red-600">{{ $message }}</span>
            @enderror
          </div>

          <!-- Fecha de compra -->
          <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Fecha de compra</label>
            <input
              type="date"
              wire:model="purchase_date"
              class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-indigo-500"
            >
            @error('purchase_date')
              <span class="text-sm text-red-600">{{ $message }}</span>
            @enderror
          </div>

          <!-- Proveedor -->
          <div class="md:col-span-2">
            <label class="mb-1 block text-sm font-medium text-gray-700">Proveedor (opcional)</label>
            <div class="flex gap-2">
              <select
                wire:model.live="supplier_id"
                class="flex-1 rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-indigo-500"
              >
                <option value="">-- Sin proveedor --</option>
                @foreach ($activeSuppliers as $supplier)
                  <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
              </select>
              <a
                href="{{ route('gas.suppliers') }}"
                class="flex items-center rounded-lg border border-indigo-600 px-3 text-indigo-600 hover:bg-indigo-50"
                title="Gestionar proveedores"
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
                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94
        3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0
        00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426
        1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724
        1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0
        001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                  />
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                  />
                </svg>
              </a>
            </div>
            @error('supplier_id')
              <span class="text-sm text-red-600">{{ $message }}</span>
            @enderror

            @if ($supplier_id)
              @php
                $selectedSupplier = $activeSuppliers->firstWhere('id', $supplier_id);
              @endphp
              @if ($selectedSupplier && $selectedSupplier->phone)
                <a
                  href="tel:{{ $selectedSupplier->phone }}"
                  class="bg-linear-to-r mt-2 flex transform items-center justify-center gap-2 rounded-lg from-green-600 to-green-700 px-4 py-3 font-semibold text-white shadow-md transition hover:from-green-700 hover:to-green-800 active:scale-95"
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
                      d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                    />
                  </svg>
                  <span class="text-sm md:text-base">Llamar a {{ $selectedSupplier->name }}</span>
                  <span
                    class="rounded bg-white/20 px-2 py-1 text-xs">{{ $selectedSupplier->phone }}</span>
                </a>
              @endif
            @endif
          </div>

          <!-- Peso -->
          <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Peso (kg)</label>
            <input
              type="number"
              step="0.1"
              wire:model="weight_kg"
              class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-indigo-500"
              placeholder="12.5"
            >
            @error('weight_kg')
              <span class="text-sm text-red-600">{{ $message }}</span>
            @enderror
          </div>

          <!-- Tipo de botella -->
          <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Tipo</label>
            <select
              wire:model="bottle_type"
              class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-indigo-500"
            >
              <option value="recarga">Recarga</option>
              <option value="nueva">Nueva</option>
            </select>
            @error('bottle_type')
              <span class="text-sm text-red-600">{{ $message }}</span>
            @enderror
          </div>

          <!-- Cantidad -->
          <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Cantidad de
              botellas</label>
            <input
              type="number"
              min="1"
              wire:model="quantity"
              class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-indigo-500"
              placeholder="1"
            >
            @error('quantity')
              <span class="text-sm text-red-600">{{ $message }}</span>
            @enderror
          </div>
        </div>

        <!-- Notas -->
        <div>
          <label class="mb-1 block text-sm font-medium text-gray-700">Notas
            (opcional)</label>
          <textarea
            wire:model="notes"
            rows="2"
            class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-indigo-500"
            placeholder="Comentarios adicionales..."
          ></textarea>
          @error('notes')
            <span class="text-sm text-red-600">{{ $message }}</span>
          @enderror
        </div>

        <div class="flex justify-end gap-3">
          <button
            type="button"
            wire:click="resetForm"
            class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
          >
            Limpiar
          </button>
          <button
            type="submit"
            class="bg-linear-to-r rounded-lg from-indigo-600 to-indigo-700 px-4 py-2 text-sm font-medium text-white hover:from-indigo-700 hover:to-indigo-800"
          >
            Guardar Compra
          </button>
        </div>
      </form>
    </div>

    @if (session()->has('success'))
      <div
        class="border-pocket-teal-500 bg-pocket-teal-100 text-pocket-teal-700 rounded border-l-4 p-4"
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

    <!-- Estadísticas rápidas -->
    <div class="grid grid-cols-2 gap-3">
      <div class="rounded-lg bg-white p-4 shadow">
        <div class="mb-1 text-xs text-gray-500">Total Gastado</div>
        <div class="text-pocket-red-500 text-xl font-bold">
          {{ number_format($stats['total'], 2) }}€
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
            @foreach ($usedSuppliers as $supplier)
              <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
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
                  🏪 {{ $purchase->supplier->name }}
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
                  <span class="ml-2">• Duró {{ $purchase->gasBottle->duration_days }}
                    días</span>
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
</div>)
