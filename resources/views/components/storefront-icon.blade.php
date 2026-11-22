@props(['name'])

<svg {{ $attributes }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
    <use href="{{ asset('icons/tabler.svg') }}#{{ $name }}"></use>
</svg>
