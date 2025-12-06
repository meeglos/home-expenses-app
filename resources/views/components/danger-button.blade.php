<button
  {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-pocket-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-pocket-red-600 active:bg-pocket-red-700 focus:outline-hidden focus:ring-2 focus:ring-pocket-red-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}
>
  {{ $slot }}
</button>
