@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center whitespace-nowrap shrink-0 px-2 lg:px-3 pt-1 border-b-2 border-accent text-xs lg:text-sm font-medium leading-5 text-ink focus:outline-none focus:border-accent transition duration-150 ease-in-out'
            : 'inline-flex items-center whitespace-nowrap shrink-0 px-2 lg:px-3 pt-1 border-b-2 border-transparent text-xs lg:text-sm font-medium leading-5 text-ink/70 hover:text-ink hover:border-ink/25 focus:outline-none focus:text-ink focus:border-ink/25 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
