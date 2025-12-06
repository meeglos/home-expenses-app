<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1"
    >
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>

  <body
    class="bg-pocket-light-gray-100 text-pocket-dark-800 dark:bg-pocket-dark-900 dark:text-pocket-light-gray-100 font-sans antialiased"
  >

    <!-- Header -->
    <header class="border-pocket-gray-300 dark:border-pocket-dark-700 border-b">
      <div class="mx-auto flex max-w-5xl items-center justify-between px-8 py-6">
        <h1 class="text-xl font-semibold tracking-tight">
          {{ config('app.name') }}
          {{-- <span class="text-sm font-normal text-gray-500">🚀</span> --}}
        </h1>

        <nav class="flex items-center gap-6 text-sm">
          <a
            href="{{ route('dashboard') }}"
            class="hover:text-pocket-gray-500 dark:hover:text-pocket-gray-300"
          >Dashboard</a>
          <div class="group relative">
            <button
              class="hover:text-pocket-gray-500 dark:hover:text-pocket-gray-300 flex items-center gap-1"
            >
              Miguel Rodríguez
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="text-pocket-gray-400 h-3 w-3"
                viewBox="0 0 20 20"
                fill="currentColor"
              >
                <path
                  fill-rule="evenodd"
                  d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.27a.75.75 0 01.02-1.06z"
                  clip-rule="evenodd"
                />
              </svg>
            </button>
          </div>
        </nav>
      </div>
    </header>

    <!-- Main content -->
    <main class="mx-auto max-w-4xl px-6 py-16">
      <h2 class="mb-12 text-center text-3xl font-semibold tracking-tight">Información del Sistema
      </h2>

      <div class="grid gap-6 md:grid-cols-3">
        <!-- Backend -->
        <section
          class="rounded-lg border border-gray-300 bg-white p-6 dark:border-gray-700 dark:bg-gray-800"
        >
          <h3 class="mb-4 text-lg font-semibold">
            <x-carbon-ibm-global-storage-architecture class="h-4 w-4" />
            Backend
          </h3>
          <ul class="space-y-1 text-sm">
            <li><span class="font-medium">PHP:</span> <span
                class="text-pocket-gray-500">{{ phpversion() }}</span></li>
            <li><span class="font-medium">Laravel:</span> <span
                class="text-pocket-gray-500">{{ Illuminate\Foundation\Application::VERSION }}</span>
            </li>
            <li><span class="font-medium">Livewire:</span> <span
                class="text-pocket-gray-500">v3.6.4</span>
            </li>
          </ul>
        </section>

        <!-- Frontend -->
        <section
          class="rounded-lg border border-gray-300 bg-white p-6 dark:border-gray-700 dark:bg-gray-800"
        >
          <h3 class="mb-4 text-lg font-semibold">
            <x-tni-css3-o class="h-4 w-4" />
            Frontend
          </h3>
          <ul class="space-y-1 text-sm">
            <li><span class="font-medium">Tailwind CSS:</span> <span
                class="text-pocket-gray-500">4.1.14</span></li>
            <li><span class="font-medium">Alpine.js:</span> <span
                class="text-pocket-gray-500">3.15.0</span></li>
          </ul>
        </section>

        <!-- Sistema -->
        <section
          class="rounded-lg border border-gray-300 bg-white p-6 dark:border-gray-700 dark:bg-gray-800"
        >
          <h3 class="mb-4 text-lg font-semibold">
            <x-fas-cogs class="h-4 w-4" />
            Sistema
          </h3>
          <ul class="space-y-1 text-sm">
            <li><span class="font-medium">Node.js:</span> <span
                class="text-pocket-gray-500">v22.20.0</span></li>
            <li><span class="font-medium">NPM:</span> <span
                class="text-pocket-gray-500">v11.6.1</span>
            </li>
            <li><span class="font-medium">Timezone:</span> <span
                class="text-pocket-gray-500">Europe/Madrid</span></li>
          </ul>
        </section>
      </div>
    </main>

    <footer
      class="border-pocket-gray-200 text-pocket-gray-500 dark:border-pocket-dark-800 border-t py-6 text-center text-xs"
    >
      {{ config('app.name') }} — Proyecto desarrollado con Laravel 12 + TALL Stack.
    </footer>

  </body>
</html>
