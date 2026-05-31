<x-app-layout>
    @section('title', 'Produits')
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div>
                <h2 class="page-title">
                    {{ __('Gestion du Stock des Produits BTP') }}
                </h2>
                <p class="page-subtitle">Consultez, gérez et importez le catalogue de matériaux de chantiers.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center bg-cream px-3 py-1.5 rounded-md border border-ink/20 hover:border-ink/25 transition">
                    @csrf
                    <label class="flex items-center cursor-pointer space-x-2">
                        <svg class="w-5 h-5 text-ink/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="text-xs font-medium text-ink/80 border-r pr-2 mr-1 border-ink/20">Fichier CSV</span>
                        <input type="file" name="file" accept=".csv" required class="text-xs text-ink/70 file:hidden cursor-pointer max-w-[140px]">
                    </label>
                    <button type="submit" class="ml-2 btn-secondary text-cream px-3 py-1 rounded-lg text-xs font-semibold transition flex items-center space-x-1">
                        <span>Importer</span>
                    </button>
                </form>

                <a href="{{ route('products.create') }}" class="inline-flex items-center btn-primary text-cream px-4 py-2 rounded-md text-sm font-semibold transition space-x-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Nouveau Produit</span>
                </a>
                
                <a href="{{ route('products.trash') }}" class="inline-flex items-center btn-muted px-3 py-2 rounded-md text-sm font-medium transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    <span>Poubelle</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="app-page">
        <div class="app-container">
            
            @if(session('success'))
                <div class="app-alert-success flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="p-1 bg-sage text-cream rounded-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-sm font-medium text-ink">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <div class="app-card">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="app-table-head">
                                <th class="py-4 px-6">Désignation & Matériau</th>
                                <th class="py-4 px-6">Catégorie</th>
                                <th class="py-4 px-6 text-right">Prix d'Achat</th>
                                <th class="py-4 px-6 text-center">Niveau de Stock</th>
                                <th class="py-4 px-6">Statut</th>
                                <th class="py-4 px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-ink/80 divide-y divide-ink/10">
                            @forelse($products as $product)
                                <tr class="hover:bg-accent/5 transition duration-150">
                                    <td class="py-4 px-6 max-w-xs">
                                        <div class="font-semibold text-ink truncate">{{ $product->name }}</div>
                                        <div class="text-xs text-ink/50 mt-0.5 line-clamp-1">{{ $product->description ?? 'Aucune description fournie.' }}</div>
                                    </td>
                                    
                                    <td class="py-4 px-6">
                                        <span class="app-badge">
                                            {{ $product->category }}
                                        </span>
                                    </td>
                                    
                                    <td class="py-4 px-6 text-right font-medium text-ink">
                                        {{ number_format($product->purchase_price, 2, ',', ' ') }} DH
                                    </td>
                                    
                                    <td class="py-4 px-6 text-center font-bold text-ink">
                                        {{ $product->stock }}
                                    </td>
                                    
                                    <td class="py-4 px-6">
                                        @if($product->stock <= 0)
                                            <span class="inline-flex items-center text-xs font-semibold text-accent">
                                                <span class="w-2 h-2 rounded-full bg-ink mr-1.5 animate-pulse"></span> Rupture
                                            </span>
                                        @elseif($product->stock <= 20)
                                            <span class="inline-flex items-center text-xs font-semibold text-accent">
                                                <span class="w-2 h-2 rounded-full bg-accent mr-1.5"></span> Stock Faible
                                            </span>
                                        @else
                                            <span class="inline-flex items-center text-xs font-semibold text-sage">
                                                <span class="w-2 h-2 rounded-full bg-sage mr-1.5"></span> Disponible
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <td class="py-4 px-6 text-right">
                                        <div class="inline-flex items-center space-x-2">
                                            <a href="{{ route('products.show', $product->id) }}" class="p-1.5 text-ink/50 hover:text-sage hover:bg-accent/15 rounded-lg transition" title="Voir les détails">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                            
                                            <a href="{{ route('products.edit', $product->id) }}" class="p-1.5 text-ink/50 hover:text-accent hover:bg-accent/15 rounded-lg transition" title="Modifier">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-2.036a5.5 5.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>

                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-ink/50 hover:text-accent hover:bg-accent/15 rounded-lg transition" title="Supprimer">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-ink/50">
                                        <div class="flex flex-col items-center justify-center space-y-2">
                                            <svg class="w-10 h-10 text-ink/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4a2 2 0 012-2m16 0h-2M4 13H6m8 4h.01M9 16h.01"></path></svg>
                                            <span class="text-sm font-medium">Aucun produit trouvé. Essayez d'importer le fichier CSV ci-dessus!</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($products, 'links'))
                    <div class="px-6 py-4 bg-sage/10 border-t border-ink/15">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>