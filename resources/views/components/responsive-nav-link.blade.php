@props(['active'])

@php
  $classes =
      $active ?? false
          ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-pocket-red-500 text-start text-base font-medium text-pocket-red-700 bg-pocket-red-50 focus:outline-hidden focus:text-pocket-red-800 focus:bg-pocket-red-100 focus:border-pocket-red-700 transition duration-150 ease-in-out'
          : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-pocket-gray-500 hover:text-pocket-dark-800 hover:bg-pocket-light-gray-100 hover:border-pocket-gray-300 focus:outline-hidden focus:text-pocket-dark-800 focus:bg-pocket-light-gray-100 focus:border-pocket-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
  {{ $slot }}
</a>
