<x-app-layout>
    @section('title', 'Entrées - Corbeille')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="page-title">
                    {{ __('Bons d\'Entrées Archivés (Poubelle)') }}
                </h2>
                <p class="page-subtitle">Consultez, restaurez ou supprimez définitivement les approvisionnements de stock archivés.</p>
            </div>
            <a href="{{ route('entries.index') }}" class="inline-flex items-center btn-muted px-4 py-2 rounded-md text-sm font-medium transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Retour aux entrées actives</span>
            </a>
        </div>
    </x-slot>

    <div class="app-page">
        <div class="app-container">
            <div class="app-card">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="app-table-head">
                                <th class="py-4 px-6">Réf. Document</th>
                                <th class="py-4 px-6">Produit BTP</th>
                                <th class="py-4 px-6">Fournisseur</th>
                                <th class="py-4 px-6">Affectation</th>
                                <th class="py-4 px-6 text-center">Quantité Reçue</th>
                                <th class="py-4 px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-ink/80 divide-y divide-ink/10">
                            @forelse($entries as $entry)
                                <tr class="hover:bg-accent/5 transition duration-150">
                                    <!-- Document -->
                                    <td class="py-4 px-6">
                                        <div class="font-bold text-ink">{{ $entry->document ?? 'N/A' }}</div>
                                        <div class="text-[11px] text-ink/50 mt-0.5">Reçu le {{ $entry->created_at->format('d/m/Y à H:i') }}</div>
                                    </td>
                                    
                                    <!-- Product -->
                                    <td class="py-4 px-6">
                                        <div class="font-semibold text-ink">{{ optional($entry->product)->name ?? 'Produit Supprimé' }}</div>
                                        <div class="text-xs text-ink/50 mt-0.5">{{ optional($entry->product)->category ?? 'Aucune catégorie' }}</div>
                                    </td>
                                    
                                    <!-- Supplier -->
                                    <td class="py-4 px-6 font-medium text-ink">
 {{ optional($entry->supplier)->name ?? 'N/A' }}
                                    </td>

                                    <!-- Affectation -->
                                    <td class="py-4 px-6">
                                        @if($entry->chantier)
                                            <span class="inline-flex items-center text-xs font-semibold bg-sage/10 text-ink/80 border border-ink/20 px-2 py-0.5 rounded-md">
 {{ $entry->chantier->name }}
                                            </span>
                                        @else
                                            <span class="text-xs text-ink/50">Dépôt Central (Global)</span>
                                        @endif
                                    </td>

                                    <!-- Quantité -->
                                    <td class="py-4 px-6 text-center font-bold text-ink">
                                        + {{ $entry->quantity }}
                                    </td>

                                    <!-- Actions -->
                                    <td class="py-4 px-6 text-right">
                                        <div class="inline-flex items-center space-x-2">
                                            <a href="{{ route('entries.restore', $entry->id) }}" class="inline-flex items-center bg-accent/15 hover:bg-accent/20 text-ink border border-accent/30 px-3 py-1.5 rounded-md text-xs font-semibold transition space-x-1" title="Restaurer l'entrée">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v6h6M4 10l6-6a9 9 0 11-3 14.32" />
                                                </svg>
                                                <span>Restaurer</span>
                                            </a>
                                            
                                            <form action="{{ route('entries.forceDelete', $entry->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer définitivement cette entrée ? Cette action est irréversible.');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center bg-accent/15 hover:bg-accent/20 text-ink border border-accent/30 px-3 py-1.5 rounded-md text-xs font-semibold transition space-x-1" title="Supprimer définitivement">
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
                                    <td colspan="6" class="py-12 text-center text-ink/50">
                                        <div class="flex flex-col items-center justify-center space-y-2">
                                            <svg class="w-10 h-10 text-ink/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a1 1 0 011 1v2H9V4a1 1 0 011-1z"></path></svg>
                                            <span class="text-sm font-medium">La poubelle est vide.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($entries, 'links'))
                    <div class="px-6 py-4 bg-sage/10 border-t border-ink/15">
                        {{ $entries->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
