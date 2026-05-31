<x-app-layout>
    @section('title', 'Sorties Stock')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="page-title">
                    {{ __('Bons de Sorties de Stock') }}
                </h2>
                <p class="page-subtitle">Gérez et suivez les sorties de stock BTP affectées aux différents chantiers et clients.</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('exits.trash') }}" class="inline-flex items-center bg-sage/10 hover:bg-ink/5 text-accent border border-ink/20 px-4 py-2 rounded-md text-sm font-medium transition space-x-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a1 1 0 011 1v2H9V4a1 1 0 011-1z"/>
                    </svg>
                    <span>Poubelle (Archivés)</span>
                </a>
                <a href="{{ route('exits.create') }}" class="inline-flex items-center btn-primary text-cream px-4 py-2 rounded-md text-sm font-semibold transition space-x-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Nouvelle Sortie</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="app-page">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="app-alert-success flex items-center space-x-3">
                    <svg class="w-5 h-5 text-sage" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="app-card">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="app-table-head">
                                <th class="py-4 px-6">Réf. Document</th>
                                <th class="py-4 px-6">Produit BTP</th>
                                <th class="py-4 px-6">Affectation</th>
                                <th class="py-4 px-6 text-center">Quantité</th>
                                <th class="py-4 px-6 text-right">Montant Total</th>
                                <th class="py-4 px-6 text-center">Statut Facturation</th>
                                <th class="py-4 px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-ink/80 divide-y divide-ink/10">
                            @forelse($exits as $exit)
                                <tr class="hover:bg-accent/5 transition duration-150">
                                    <!-- Document -->
                                    <td class="py-4 px-6">
                                        <div class="font-bold text-ink">{{ $exit->document ?? 'N/A' }}</div>
                                        <div class="text-[11px] text-ink/50 mt-0.5">Le {{ $exit->created_at->format('d/m/Y à H:i') }}</div>
                                    </td>
                                    
                                    <!-- Product -->
                                    <td class="py-4 px-6">
                                        <div class="font-semibold text-ink">{{ optional($exit->product)->name ?? 'Produit Supprimé' }}</div>
                                        <div class="text-xs text-ink/50 mt-0.5">{{ optional($exit->product)->category ?? 'Aucune catégorie' }}</div>
                                    </td>
                                    
                                    <!-- Affectation (Customer & Chantier) -->
                                    <td class="py-4 px-6">
                                        @if($exit->chantier)
                                            <div class="inline-flex items-center text-xs font-semibold bg-accent/15 text-ink border border-accent/30 px-2 py-0.5 rounded-md mb-1">
 {{ $exit->chantier->name }}
                                            </div>
                                        @endif
                                        @if($exit->customer)
                                            <div class="text-xs text-ink/70 font-medium">Client: {{ $exit->customer->name }}</div>
                                        @else
                                            <div class="text-xs text-ink/50">Aucun client spécifié</div>
                                        @endif
                                    </td>

                                    <!-- Quantité -->
                                    <td class="py-4 px-6 text-center font-bold text-ink">
                                        {{ $exit->quantity }}
                                    </td>

                                    <!-- Montant Total -->
                                    <td class="py-4 px-6 text-right font-black text-ink">
                                        {{ number_format($exit->quantity * $exit->unit_price, 2, ',', ' ') }} DH
                                    </td>

                                    <!-- Statut Facturation -->
                                    <td class="py-4 px-6 text-center">
                                        @if($exit->payment_status == 'paid')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-sage/15 text-ink border border-sage/30">
                                                Payé
                                            </span>
                                        @elseif($exit->payment_status == 'partial')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-accent/15 text-ink border border-accent/30">
                                                Partiel
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-accent/15 text-ink border border-accent/30">
                                                Non Payé
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Actions -->
                                    <td class="py-4 px-6 text-right">
                                        <div class="inline-flex items-center space-x-2">
                                            <a href="{{ route('exits.show', $exit) }}" class="inline-flex items-center bg-sage/10 hover:bg-ink/5 text-ink border border-ink/20 px-3 py-1.5 rounded-md text-xs font-semibold transition space-x-1" title="Voir les détails">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                <span>Détails</span>
                                            </a>

                                            <a href="{{ route('exits.edit', $exit) }}" class="inline-flex items-center bg-accent/15 hover:bg-accent/20 text-ink border border-accent/30 px-3 py-1.5 rounded-md text-xs font-semibold transition space-x-1" title="Modifier">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-2.036a5.5 5.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                </svg>
                                                <span>Modifier</span>
                                            </a>

                                            <form action="{{ route('exits.destroy', $exit) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir archiver cette sortie de stock ?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center bg-accent/15 hover:bg-accent/20 text-ink border border-accent/30 px-3 py-1.5 rounded-md text-xs font-semibold transition space-x-1" title="Archiver la sortie">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a1 1 0 011 1v2H9V4a1 1 0 011-1z"/>
                                                    </svg>
                                                    <span>Archiver</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-ink/50">
                                        <div class="flex flex-col items-center justify-center space-y-2">
                                            <svg class="w-10 h-10 text-ink/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                            <span class="text-sm font-medium">Aucun bon de sortie enregistré.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($exits, 'links'))
                    <div class="px-6 py-4 bg-sage/10 border-t border-ink/15">
                        {{ $exits->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
