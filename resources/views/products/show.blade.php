<x-app-layout>
    @section('title', 'Détails du Produit')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Détails du Produit BTP') }}
            </h2>
            <a href="{{ route('products.index') }}" class="inline-flex items-center bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl text-sm font-medium transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Retour au catalogue</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-8">
                <div class="space-y-6">
                    <!-- Name & Category -->
                    <div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100 mb-2">
                            {{ $product->category }}
                        </span>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h3>
                    </div>

                    <hr class="border-gray-100">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Purchase Price -->
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <span class="text-xs text-gray-400 font-medium uppercase tracking-wider">Prix d'Achat</span>
                            <div class="text-lg font-bold text-gray-900 mt-1">{{ number_format($product->purchase_price, 2, ',', ' ') }} DH</div>
                        </div>

                        <!-- Stock Level -->
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <span class="text-xs text-gray-400 font-medium uppercase tracking-wider">Niveau de Stock</span>
                            <div class="flex items-center mt-1">
                                <span class="text-lg font-bold text-gray-900 mr-2">{{ $product->stock }}</span>
                                @if($product->stock <= 0)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-100">
                                        Rupture
                                    </span>
                                @elseif($product->stock <= 20)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                                        Faible
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        Disponible
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="space-y-2">
                        <span class="text-sm font-semibold text-gray-700 block">Description</span>
                        <p class="text-gray-600 leading-relaxed bg-gray-50/50 rounded-xl p-4 border border-gray-100 min-h-[100px]">{{ $product->description ?? 'Aucune description fournie.' }}</p>
                    </div>

                    <div class="flex justify-end space-x-3 border-t border-gray-100 pt-6">
                        <a href="{{ route('products.index') }}" class="inline-flex items-center px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition">
                            Fermer
                        </a>
                        <a href="{{ route('products.edit', $product) }}" class="inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm shadow-sm transition">
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
