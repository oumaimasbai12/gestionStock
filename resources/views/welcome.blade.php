<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Stocket - Système de Gestion des Stocks</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-cream text-ink">

<!-- Hero -->
<section class="border-b-2 border-ink/15">
    <div class="max-w-7xl mx-auto px-6 py-20 md:py-28">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-12">
            <div class="max-w-xl">
                <p class="text-xs font-bold uppercase tracking-widest text-sage mb-4">Distribution BTP</p>
                <h1 class="text-4xl sm:text-5xl font-bold text-ink leading-tight mb-6">Stocket</h1>
                <p class="text-lg text-ink/70 mb-8 leading-relaxed">
                    Gestion efficace des stocks pour les produits, fournisseurs, clients et mouvements de stock.
                </p>
                <div class="flex flex-wrap gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-secondary px-6 py-3">Tableau de bord</a>
                        @else
                            <a href="{{ route('login') }}" class="btn-secondary px-6 py-3">Se connecter</a>
                        @endauth
                    @endif
                </div>
            </div>
            <div class="flex justify-center md:justify-end">
                <x-application-mark class="w-48 h-48 md:w-56 md:h-56 object-contain" />
            </div>
        </div>
    </div>
</section>

<!-- About -->
<section class="max-w-7xl mx-auto px-6 py-16">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <div class="app-panel">
            <h2 class="text-2xl font-bold text-ink mb-4">Qu'est-ce que Stocket ?</h2>
            <p class="text-ink/70 leading-relaxed">
                Stocket est un système complet de gestion des stocks développé sous Laravel, conçu pour vous aider à gérer vos produits, fournisseurs, clients et mouvements de stock de manière simple et efficace. Avec des tableaux de bord en temps réel et des graphiques interactifs, le suivi de vos stocks n'a jamais été aussi facile.
            </p>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div class="app-panel text-center">
                <div class="text-3xl font-black text-accent">BI</div>
                <p class="text-xs text-ink/60 mt-2 uppercase tracking-wider font-semibold">Tableaux de bord</p>
            </div>
            <div class="app-panel text-center">
                <div class="text-3xl font-black text-sage">24/7</div>
                <p class="text-xs text-ink/60 mt-2 uppercase tracking-wider font-semibold">Suivi stock</p>
            </div>
            <div class="app-panel text-center col-span-2">
                <div class="text-3xl font-black text-ink">BTP</div>
                <p class="text-xs text-ink/60 mt-2 uppercase tracking-wider font-semibold">Matériaux de chantier</p>
            </div>
        </div>
    </div>
</section>

<!-- How it works -->
<section class="border-t-2 border-ink/15 bg-sage/5">
    <div class="max-w-7xl mx-auto px-6 py-16">
        <h2 class="text-2xl font-bold text-ink text-center mb-12">Comment ça marche ?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="app-panel relative">
                <div class="absolute -top-3 -left-3 w-8 h-8 bg-sage text-cream rounded-md flex items-center justify-center text-sm font-bold">1</div>
                <h3 class="text-lg font-bold text-ink mt-2 mb-2">Configuration</h3>
                <p class="text-sm text-ink/70">Définissez vos produits, fournisseurs et clients pour commencer à gérer vos stocks.</p>
            </div>
            <div class="app-panel relative">
                <div class="absolute -top-3 -left-3 w-8 h-8 bg-accent text-cream rounded-md flex items-center justify-center text-sm font-bold">2</div>
                <h3 class="text-lg font-bold text-ink mt-2 mb-2">Mouvements</h3>
                <p class="text-sm text-ink/70">Enregistrez rapidement et précisément les entrées et sorties de stock.</p>
            </div>
            <div class="app-panel relative">
                <div class="absolute -top-3 -left-3 w-8 h-8 bg-ink text-cream rounded-md flex items-center justify-center text-sm font-bold">3</div>
                <h3 class="text-lg font-bold text-ink mt-2 mb-2">Analyses</h3>
                <p class="text-sm text-ink/70">Consultez des rapports et des tableaux de bord interactifs pour prendre des décisions basées sur les données.</p>
            </div>
        </div>
    </div>
</section>

<!-- Use cases -->
<section class="max-w-7xl mx-auto px-6 py-16">
    <h2 class="text-2xl font-bold text-ink text-center mb-3">Cas d'utilisation</h2>
    <p class="text-ink/70 text-center mb-10">Stocket s'adapte aux différents besoins du secteur BTP.</p>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="app-panel text-center">
            <div class="w-12 h-12 mx-auto mb-4 bg-accent/15 border-2 border-accent/30 rounded-md flex items-center justify-center">
                <svg class="h-6 w-6 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-ink mb-2">Chantiers</h3>
            <p class="text-sm text-ink/70">Suivez la consommation de matériaux par site de construction.</p>
        </div>
        <div class="app-panel text-center">
            <div class="w-12 h-12 mx-auto mb-4 bg-sage/15 border-2 border-sage/30 rounded-md flex items-center justify-center">
                <svg class="h-6 w-6 text-sage" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-ink mb-2">Entrepôts</h3>
            <p class="text-sm text-ink/70">Maintenez un inventaire à jour et recevez des alertes stock faible.</p>
        </div>
        <div class="app-panel text-center">
            <div class="w-12 h-12 mx-auto mb-4 bg-ink/10 border-2 border-ink/20 rounded-md flex items-center justify-center">
                <svg class="h-6 w-6 text-ink" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-ink mb-2">Distribution</h3>
            <p class="text-sm text-ink/70">Contrôlez le flux de produits et suivez les impayés clients.</p>
        </div>
    </div>
</section>

<footer class="border-t-2 border-ink/15 py-8">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <p class="text-sm text-ink/60">&copy; {{ date('Y') }} Stocket</p>
    </div>
</footer>

</body>
</html>
