@props(['active'])

@php
  $classes =
      $active ?? false
          ? 'inline-flex items-center px-1 pt-1 border-b-2 border-pocket-red-500 text-sm font-medium leading-5 text-pocket-dark-900 focus:outline-hidden focus:border-pocket-red-600 transition duration-150 ease-in-out'
          : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-pocket-gray-500 hover:text-pocket-dark-800 hover:border-pocket-gray-300 focus:outline-hidden focus:text-pocket-dark-800 focus:border-pocket-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
  {{ $slot }}
</a>
