<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-cream border-2 border-ink/20 rounded-md font-semibold text-xs text-ink uppercase tracking-widest hover:bg-ink/5 focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 focus:ring-offset-cream disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
