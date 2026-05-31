@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-accent text-start text-base font-medium text-ink bg-accent/10 focus:outline-none focus:text-ink focus:bg-accent/15 focus:border-accent transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-ink/70 hover:text-ink hover:bg-ink/5 hover:border-ink/20 focus:outline-none focus:text-ink focus:bg-ink/5 focus:border-ink/20 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
