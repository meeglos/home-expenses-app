<x-app-layout>
  <x-slot name="header">
    <h2 class="text-pocket-dark-900 text-xl font-semibold leading-tight">
      {{ __('Dashboard') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="shadow-xs overflow-hidden bg-white sm:rounded-lg">
        <div class="text-pocket-dark-900 p-6">
          {{ __("You're logged in!") }}
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
