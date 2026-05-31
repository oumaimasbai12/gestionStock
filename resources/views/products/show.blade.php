<x-app-layout>
    @section('title', 'Détails du Produit')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="page-title">
                {{ __('Détails du Produit BTP') }}
            </h2>
            <a href="{{ route('products.index') }}" class="inline-flex items-center btn-muted px-4 py-2 rounded-md text-sm font-medium transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Retour au catalogue</span>
            </a>
        </div>
    </x-slot>

    <div class="app-page">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="app-card p-8">
                <div class="space-y-6">
                    <!-- Name & Category -->
                    <div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-accent/15 text-ink border border-accent/30 mb-2">
                            {{ $product->category }}
                        </span>
                        <h3 class="text-2xl font-bold text-ink">{{ $product->name }}</h3>
                    </div>

                    <hr class="border-ink/15">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Purchase Price -->
                        <div class="bg-sage/10 rounded-md p-4 border border-ink/15">
                            <span class="text-xs text-ink/50 font-medium uppercase tracking-wider">Prix d'Achat</span>
                            <div class="text-lg font-bold text-ink mt-1">{{ number_format($product->purchase_price, 2, ',', ' ') }} DH</div>
                        </div>

                        <!-- Stock Level -->
                        <div class="bg-sage/10 rounded-md p-4 border border-ink/15">
                            <span class="text-xs text-ink/50 font-medium uppercase tracking-wider">Niveau de Stock</span>
                            <div class="flex items-center mt-1">
                                <span class="text-lg font-bold text-ink mr-2">{{ $product->stock }}</span>
                                @if($product->stock <= 0)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-accent/15 text-ink border border-accent/30">
                                        Rupture
                                    </span>
                                @elseif($product->stock <= 20)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-accent/15 text-ink border border-accent/30">
                                        Faible
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-sage/15 text-ink border border-sage/30">
                                        Disponible
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="space-y-2">
                        <span class="text-sm font-semibold text-ink block">Description</span>
                        <p class="text-ink/80 leading-relaxed bg-sage/10/50 rounded-md p-4 border border-ink/15 min-h-[100px]">{{ $product->description ?? 'Aucune description fournie.' }}</p>
                    </div>

                    <div class="flex justify-end space-x-3 border-t border-ink/15 pt-6">
                        <a href="{{ route('products.index') }}" class="inline-flex items-center px-5 py-2.5 btn-muted font-semibold rounded-md text-sm transition">
                            Fermer
                        </a>
                        <a href="{{ route('products.edit', $product) }}" class="inline-flex items-center px-5 py-2.5 btn-primary text-cream font-semibold rounded-md text-sm transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 3.487a2.125 2.125 0 013.006 3.006L7.5 18.862l-4.5 1.5 1.5-4.5L16.862 3.487z" />
                            </svg>
                            Modifier le produit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
