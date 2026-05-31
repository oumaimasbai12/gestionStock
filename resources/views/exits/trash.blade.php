<x-app-layout>
    @section('title', 'Sorties - Corbeille')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Bons de Sorties Archivés (Poubelle)') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Consultez, restaurez ou supprimez définitivement les bons de sorties supprimés temporairement.</p>
            </div>
            <a href="{{ route('exits.index') }}" class="inline-flex items-center bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl text-sm font-medium transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Retour aux sorties actives</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-gray-400 text-xs uppercase tracking-wider font-semibold">
                                <th class="py-4 px-6">Réf. Document</th>
                                <th class="py-4 px-6">Produit BTP</th>
                                <th class="py-4 px-6">Affectation</th>
                                <th class="py-4 px-6 text-center">Quantité</th>
                                <th class="py-4 px-6 text-right">Montant Total</th>
                                <th class="py-4 px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-600 divide-y divide-gray-50">
                            @forelse($exits as $exit)
                                <tr class="hover:bg-gray-50/80 transition duration-150">
                                    <!-- Document -->
                                    <td class="py-4 px-6">
                                        <div class="font-bold text-gray-900">{{ $exit->document ?? 'N/A' }}</div>
                                        <div class="text-[11px] text-gray-400 mt-0.5">Le {{ $exit->created_at->format('d/m/Y à H:i') }}</div>
                                    </td>
                                    
                                    <!-- Product -->
                                    <td class="py-4 px-6">
                                        <div class="font-semibold text-gray-900">{{ optional($exit->product)->name ?? 'Produit Supprimé' }}</div>
                                        <div class="text-xs text-gray-400 mt-0.5">{{ optional($exit->product)->category ?? 'Aucune catégorie' }}</div>
                                    </td>
                                    
                                    <!-- Affectation -->
                                    <td class="py-4 px-6">
                                        @if($exit->chantier)
                                            <div class="inline-flex items-center text-xs font-semibold bg-gray-50 text-gray-600 border border-gray-200 px-2 py-0.5 rounded-md mb-1">
 {{ $exit->chantier->name }}
                                            </div>
                                        @endif
                                        @if($exit->customer)
                                            <div class="text-xs text-gray-500">Client: {{ $exit->customer->name }}</div>
                                        @endif
                                    </td>

                                    <!-- Quantité -->
                                    <td class="py-4 px-6 text-center font-bold text-gray-900">
                                        {{ $exit->quantity }}
                                    </td>

                                    <!-- Montant Total -->
                                    <td class="py-4 px-6 text-right font-black text-gray-900">
                                        {{ number_format($exit->quantity * $exit->unit_price, 2, ',', ' ') }} DH
                                    </td>

                                    <!-- Actions -->
                                    <td class="py-4 px-6 text-right">
                                        <div class="inline-flex items-center space-x-2">
                                            <a href="{{ route('exits.restore', $exit->id) }}" class="inline-flex items-center bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-100 px-3 py-1.5 rounded-xl text-xs font-semibold shadow-sm transition space-x-1" title="Restaurer la sortie">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v6h6M4 10l6-6a9 9 0 11-3 14.32" />
                                                </svg>
                                                <span>Restaurer</span>
                                            </a>
                                            
                                            <form action="{{ route('exits.forceDelete', $exit->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer définitivement cette sortie ? Cette action est irréversible.');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center bg-red-50 hover:bg-red-100 text-red-700 border border-red-100 px-3 py-1.5 rounded-xl text-xs font-semibold shadow-sm transition space-x-1" title="Supprimer définitivement">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a1 1 0 011 1v2H9V4a1 1 0 011-1z"/>
                                                    </svg>
                                                    <span>Définitivement</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-gray-400">
                                        <div class="flex flex-col items-center justify-center space-y-2">
                                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a1 1 0 011 1v2H9V4a1 1 0 011-1z"></path></svg>
                                            <span class="text-sm font-medium">La poubelle est vide.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($exits, 'links'))
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                        {{ $exits->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
