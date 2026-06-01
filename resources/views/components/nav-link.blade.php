@props(['active'])

@php
$classes = ($active ?? false)
    ? 'flex items-center w-full min-w-0 px-3 py-2.5 rounded-md text-sm font-semibold text-cream bg-accent transition-colors'
    : 'flex items-center w-full min-w-0 px-3 py-2.5 rounded-md text-sm font-medium text-ink/80 hover:bg-accent/10 hover:text-ink transition-colors';
@endphp

<a
    {{ $attributes->merge(['class' => $classes]) }}
    wire:navigate
    wire:navigate.hover
>
    {{ $slot }}
</a>
