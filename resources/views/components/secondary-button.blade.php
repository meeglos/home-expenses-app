<button
  {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white border border-pocket-gray-300 rounded-md font-semibold text-xs text-pocket-dark-800 uppercase tracking-widest shadow-xs hover:bg-pocket-light-gray-50 focus:outline-hidden focus:ring-2 focus:ring-pocket-teal-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}
>
  {{ $slot }}
</button>
