@props(['status'])

@if ($status)
  <div {{ $attributes->merge(['class' => 'font-medium text-sm text-pocket-teal-600']) }}>
    {{ $status }}
  </div>
@endif
