<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1"
    >
    <meta
      name="csrf-token"
      content="{{ csrf_token() }}"
    >

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link
      rel="preconnect"
      href="https://fonts.bunny.net"
    >
    <link
      href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
      rel="stylesheet"
    />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class="text-pocket-dark-900 font-sans antialiased">
    <div
      class="bg-pocket-light-gray-100 flex min-h-screen flex-col items-center pt-6 sm:justify-center sm:pt-0"
    >
      <div>
        <a href="/">
          <x-application-logo class="text-pocket-gray-500 h-20 w-20 fill-current" />
        </a>
      </div>

      <div
        class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg"
      >
        {{ $slot }}
      </div>
    </div>
  </body>
</html>
