<x-app-layout>
    @section('title', 'Détails Entrée')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="page-title">
                {{ __('Détails du Bon d\'Entrée') }}
            </h2>
            <a href="{{ route('entries.index') }}" class="inline-flex items-center btn-muted px-4 py-2 rounded-md text-sm font-medium transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Retour aux entrées</span>
            </a>
        </div>
    </x-slot>

    <div class="app-page">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="app-card p-8">
                <div class="space-y-6">
                    
                    <!-- Header Ref -->
                    <div class="flex items-center justify-between border-b border-ink/10 pb-4">
                        <div>
                            <span class="text-xs text-ink/50 font-medium uppercase tracking-wider block">Référence Document</span>
                            <span class="text-xl font-bold text-ink">{{ $entry->document ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-ink/50 font-medium uppercase tracking-wider block text-right">Date d'approvisionnement</span>
                            <span class="text-sm font-semibold text-ink block text-right">{{ optional($entry->created_at)->format('d/m/Y à H:i') ?? '-' }}</span>
                        </div>
                    </div>

                    <!-- Main Columns Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Product Info -->
                        <div class="bg-sage/10 rounded-md p-4 border border-ink/15">
                            <span class="text-xs text-ink/50 font-medium uppercase tracking-wider">Produit BTP Reçu</span>
                            <div class="text-base font-bold text-ink mt-1">{{ optional($entry->product)->name ?? 'Produit Supprimé' }}</div>
                            <div class="text-xs text-ink/50 mt-0.5">Catégorie: {{ optional($entry->product)->category ?? 'N/A' }}</div>
                        </div>

                        <!-- Fournisseur Info -->
                        <div class="bg-sage/10 rounded-md p-4 border border-ink/15">
                            <span class="text-xs text-ink/50 font-medium uppercase tracking-wider">Fournisseur</span>
                            <div class="text-base font-bold text-ink mt-1">
 {{ optional($entry->supplier)->name ?? 'N/A' }}
                            </div>
                        </div>

                        <!-- Chantier Info -->
                        <div class="bg-sage/10 rounded-md p-4 border border-ink/15">
                            <span class="text-xs text-ink/50 font-medium uppercase tracking-wider">Affectation Chantier</span>
                            <div class="text-base font-bold text-ink mt-1">
                                {{ $entry->chantier ? '' . $entry->chantier->name : 'Dépôt Central (Global)' }}
                            </div>
                        </div>

                        <!-- Quantité Reçue -->
                        <div class="bg-sage/10 rounded-md p-4 border border-ink/15">
                            <span class="text-xs text-ink/50 font-medium uppercase tracking-wider block">Quantité Reçue</span>
                            <div class="text-xl font-black text-sage mt-1">
                                + {{ $entry->quantity }} unités
                            </div>
                        </div>
                    </div>

                    <!-- Action Footer -->
                    <div class="flex justify-end space-x-3 border-t border-ink/15 pt-6">
                        <a href="{{ route('entries.index') }}" class="inline-flex items-center px-5 py-2.5 btn-muted font-semibold rounded-md text-sm transition">
                            Fermer
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
