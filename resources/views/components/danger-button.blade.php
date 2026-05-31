<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-4 py-2 bg-ink border border-transparent rounded-md font-semibold text-xs text-cream uppercase tracking-widest hover:bg-ink/90 active:bg-ink focus:outline-none focus:ring-2 focus:ring-ink focus:ring-offset-2 focus:ring-offset-cream transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
