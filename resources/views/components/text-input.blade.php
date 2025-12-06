@props(['disabled' => false])

<input
  @disabled($disabled)
  {{ $attributes->merge(['class' => 'border-pocket-gray-300 focus:border-pocket-red-500 focus:ring-pocket-red-500 rounded-md shadow-xs']) }}
>
