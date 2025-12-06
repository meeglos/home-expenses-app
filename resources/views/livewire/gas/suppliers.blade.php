<div class="min-h-screen bg-gray-50">
  <div class="bg-linear-to-r sticky top-0 z-10 from-blue-600 to-blue-700 text-white shadow-lg">
    <div class="mx-auto max-w-7xl px-4 py-3">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
          <a href="{{ route('gas.dashboard') }}">
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
          <h1 class="text-base font-bold md:text-xl">🏪 Proveedores</h1>
        </div>
        <x-dropdown
          align="right"
          width="48"
        >
          <x-slot name="trigger">
            <button
              class="focus:outline-hidden inline-flex items-center rounded-md border border-transparent bg-white/10 p-2 text-sm font-medium leading-4 text-white transition duration-150 ease-in-out hover:bg-white/20 md:px-4 md:py-2"
            >
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
            <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
            <form
              method="POST"
              action="{{ route('logout') }}"
            >
              @csrf
              <x-dropdown-link
                :href="route('logout')"
                onclick="event.preventDefault(); this.closest('form').submit();"
              >{{ __('Log Out') }}</x-dropdown-link>
            </form>
          </x-slot>
        </x-dropdown>
      </div>
    </div>
  </div>

  <div class="mx-auto max-w-7xl space-y-4 px-4 py-6">
    @if (!$showForm)
      <button
        wire:click="$set('showForm', true)"
        class="bg-linear-to-r flex w-full items-center justify-center gap-2 rounded-lg from-blue-600 to-blue-700 px-4 py-3 font-semibold text-white shadow-md transition hover:from-blue-700 hover:to-blue-800"
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
        <span>Agregar Proveedor</span>
      </button>
    @endif

    @if ($showForm)
      <div class="overflow-hidden rounded-lg bg-white shadow-md">
        <div class="bg-linear-to-r border-b from-blue-100 to-indigo-100 px-4 py-3">
          <h3 class="text-lg font-bold">
            {{ $editingId ? '✏️ Editar Proveedor' : '➕ Nuevo Proveedor' }}</h3>
        </div>
        <form
          wire:submit="save"
          class="space-y-4 p-4"
        >
          <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div class="md:col-span-2">
              <label class="mb-1 block text-sm font-medium text-gray-700">Nombre <span
                  class="text-red-500"
                >*</span></label>
              <input
                type="text"
                wire:model="name"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
                placeholder="Ej: Repsol, Cepsa..."
                required
              >
              @error('name')
                <span class="text-sm text-red-600">{{ $message }}</span>
              @enderror
            </div>
            <div>
              <label class="mb-1 block text-sm font-medium text-gray-700">Teléfono</label>
              <input
                type="tel"
                wire:model="phone"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
                placeholder="912345678"
              >
              @error('phone')
                <span class="text-sm text-red-600">{{ $message }}</span>
              @enderror
            </div>
            <div>
              <label class="mb-1 block text-sm font-medium text-gray-700">Email</label>
              <input
                type="email"
                wire:model="email"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
                placeholder="pedidos@proveedor.com"
              >
              @error('email')
                <span class="text-sm text-red-600">{{ $message }}</span>
              @enderror
            </div>
            <div class="md:col-span-2">
              <label class="mb-1 block text-sm font-medium text-gray-700">Notas</label>
              <textarea
                wire:model="notes"
                rows="2"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500"
                placeholder="Información adicional..."
              ></textarea>
              @error('notes')
                <span class="text-sm text-red-600">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="flex justify-end gap-3">
            <button
              type="button"
              wire:click="resetForm"
              class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >Cancelar</button>
            <button
              type="submit"
              class="bg-linear-to-r rounded-lg from-blue-600 to-blue-700 px-4 py-2 text-sm font-medium text-white hover:from-blue-700 hover:to-blue-800"
            >{{ $editingId ? 'Actualizar' : 'Guardar' }}</button>
          </div>
        </form>
      </div>
    @endif

    @if (session()->has('success'))
      <div class="rounded border-l-4 border-green-500 bg-green-100 p-4 text-green-700">
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

    <div class="space-y-3">
      @forelse($suppliers as $supplier)
        <div
          class="@if (!$supplier->is_active) opacity-60 @endif overflow-hidden rounded-lg bg-white shadow-md"
        >
          <div class="p-4">
            <div class="mb-3 flex items-start justify-between">
              <div class="flex-1">
                <h3 class="text-lg font-bold text-gray-900">🏪 {{ $supplier->name }}</h3>
                @if (!$supplier->is_active)
                  <span class="text-xs text-red-500">Inactivo</span>
                @endif
              </div>
              <div class="flex gap-2">
                <button
                  wire:click="toggleActive({{ $supplier->id }})"
                  class="rounded p-1 hover:bg-gray-100"
                  title="{{ $supplier->is_active ? 'Desactivar' : 'Activar' }}"
                >
                  <svg
                    class="@if ($supplier->is_active) text-green-600 @else text-gray-400 @endif h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                  </svg>
                </button>
                <button
                  wire:click="edit({{ $supplier->id }})"
                  class="rounded p-1 hover:bg-gray-100"
                  title="Editar"
                >
                  <svg
                    class="h-5 w-5 text-blue-600"
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
                </button>
                <button
                  wire:click="delete({{ $supplier->id }})"
                  wire:confirm="¿Estás seguro de eliminar este proveedor?"
                  class="rounded p-1 hover:bg-gray-100"
                  title="Eliminar"
                >
                  <svg
                    class="h-5 w-5 text-red-600"
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
            <div class="space-y-2 text-sm text-gray-600">
              @if ($supplier->phone)
                <div class="flex items-center gap-2">
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
                      d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                    />
                  </svg>
                  <a
                    href="tel:{{ $supplier->phone }}"
                    class="hover:text-blue-600"
                  >{{ $supplier->phone }}</a>
                </div>
              @endif
              @if ($supplier->email)
                <div class="flex items-center gap-2">
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
                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                    />
                  </svg>
                  <a
                    href="mailto:{{ $supplier->email }}"
                    class="hover:text-blue-600"
                  >{{ $supplier->email }}</a>
                </div>
              @endif
              @if ($supplier->notes)
                <div class="mt-2 border-t pt-2 text-sm text-gray-600">{{ $supplier->notes }}</div>
              @endif
            </div>
          </div>
        </div>
      @empty
        <div class="rounded-lg bg-white p-8 text-center shadow-md">
          <p class="text-gray-500">No hay proveedores registrados</p>
          <p class="mt-2 text-sm text-gray-400">Agrega un proveedor para gestionar mejor tus
            compras</p>
        </div>
      @endforelse
    </div>
  </div>
</div>
