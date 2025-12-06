@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-pocket-dark-800']) }}>
  {{ $value ?? $slot }}
</label>
