<x-app-layout>
    @section('title', 'Produits')
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Gestion du Stock des Produits BTP') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Consultez, gérez et importez le catalogue de matériaux de chantiers.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center bg-white px-3 py-1.5 rounded-xl border border-gray-200 shadow-sm hover:border-gray-300 transition">
                    @csrf
                    <label class="flex items-center cursor-pointer space-x-2">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="text-xs font-medium text-gray-600 border-r pr-2 mr-1 border-gray-200">Fichier CSV</span>
                        <input type="file" name="file" accept=".csv" required class="text-xs text-gray-500 file:hidden cursor-pointer max-w-[140px]">
                    </label>
                    <button type="submit" class="ml-2 bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1 rounded-lg text-xs font-semibold shadow-sm transition flex items-center space-x-1">
                        <span>Importer</span>
                    </button>
                </form>

                <a href="{{ route('products.create') }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-sm transition space-x-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Nouveau Produit</span>
                </a>
                
                <a href="{{ route('products.trash') }}" class="inline-flex items-center bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-xl text-sm font-medium transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    <span>Poubelle</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center space-x-3">
                        <div class="p-1 bg-emerald-500 text-white rounded-full">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-sm font-medium text-emerald-800">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-gray-400 text-xs uppercase tracking-wider font-semibold">
                                <th class="py-4 px-6">Désignation & Matériau</th>
                                <th class="py-4 px-6">Catégorie</th>
                                <th class="py-4 px-6 text-right">Prix d'Achat</th>
                                <th class="py-4 px-6 text-center">Niveau de Stock</th>
                                <th class="py-4 px-6">Statut</th>
                                <th class="py-4 px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-600 divide-y divide-gray-50">
                            @forelse($products as $product)
                                <tr class="hover:bg-gray-50/80 transition duration-150">
                                    <td class="py-4 px-6 max-w-xs">
                                        <div class="font-semibold text-gray-900 truncate">{{ $product->name }}</div>
                                        <div class="text-xs text-gray-400 mt-0.5 line-clamp-1">{{ $product->description ?? 'Aucune description fournie.' }}</div>
                                    </td>
                                    
                                    <td class="py-4 px-6">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                            {{ $product->category }}
                                        </span>
                                    </td>
                                    
                                    <td class="py-4 px-6 text-right font-medium text-gray-900">
                                        {{ number_format($product->purchase_price, 2, ',', ' ') }} DH
                                    </td>
                                    
                                    <td class="py-4 px-6 text-center font-bold text-gray-800">
                                        {{ $product->stock }}
                                    </td>
                                    
                                    <td class="py-4 px-6">
                                        @if($product->stock <= 0)
                                            <span class="inline-flex items-center text-xs font-semibold text-red-600">
                                                <span class="w-2 h-2 rounded-full bg-red-600 mr-1.5 animate-pulse"></span> Rupture
                                            </span>
                                        @elseif($product->stock <= 20)
                                            <span class="inline-flex items-center text-xs font-semibold text-amber-600">
                                                <span class="w-2 h-2 rounded-full bg-amber-500 mr-1.5"></span> Stock Faible
                                            </span>
                                        @else
                                            <span class="inline-flex items-center text-xs font-semibold text-emerald-600">
                                                <span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5"></span> Disponible
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <td class="py-4 px-6 text-right">
                                        <div class="inline-flex items-center space-x-2">
                                            <a href="{{ route('products.show', $product->id) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Voir les détails">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                            
                                            <a href="{{ route('products.edit', $product->id) }}" class="p-1.5 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition" title="Modifier">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-2.036a5.5 5.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>

                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Supprimer">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-gray-400">
                                        <div class="flex flex-col items-center justify-center space-y-2">
                                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4a2 2 0 012-2m16 0h-2M4 13H6m8 4h.01M9 16h.01"></path></svg>
                                            <span class="text-sm font-medium">Aucun produit trouvé. Essayez d'importer le fichier CSV ci-dessus!</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($products, 'links'))
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>