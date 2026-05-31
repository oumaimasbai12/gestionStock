<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Détails du Bon d\'Entrée') }}
            </h2>
            <a href="{{ route('entries.index') }}" class="inline-flex items-center bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl text-sm font-medium transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Retour aux entrées</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-8">
                <div class="space-y-6">
                    
                    <!-- Header Ref -->
                    <div class="flex items-center justify-between border-b border-gray-50 pb-4">
                        <div>
                            <span class="text-xs text-gray-400 font-medium uppercase tracking-wider block">Référence Document</span>
                            <span class="text-xl font-bold text-gray-900">{{ $stockEntry->document ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 font-medium uppercase tracking-wider block text-right">Date d'approvisionnement</span>
                            <span class="text-sm font-semibold text-gray-700 block text-right">{{ $stockEntry->created_at->format('d/m/Y à H:i') }}</span>
                        </div>
                    </div>

                    <!-- Main Columns Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Product Info -->
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <span class="text-xs text-gray-400 font-medium uppercase tracking-wider">Produit BTP Reçu</span>
                            <div class="text-base font-bold text-gray-900 mt-1">{{ optional($stockEntry->product)->name ?? 'Produit Supprimé' }}</div>
                            <div class="text-xs text-gray-400 mt-0.5">Catégorie: {{ optional($stockEntry->product)->category ?? 'N/A' }}</div>
                        </div>

                        <!-- Fournisseur Info -->
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <span class="text-xs text-gray-400 font-medium uppercase tracking-wider">Fournisseur</span>
                            <div class="text-base font-bold text-gray-900 mt-1">
                                🏢 {{ optional($stockEntry->supplier)->name ?? 'N/A' }}
                            </div>
                        </div>

                        <!-- Chantier Info -->
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <span class="text-xs text-gray-400 font-medium uppercase tracking-wider">Affectation Chantier</span>
                            <div class="text-base font-bold text-gray-900 mt-1">
                                {{ $stockEntry->chantier ? '🏗️ ' . $stockEntry->chantier->name : 'Dépôt Central (Global)' }}
                            </div>
                        </div>

                        <!-- Quantité Reçue -->
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <span class="text-xs text-gray-400 font-medium uppercase tracking-wider block">Quantité Reçue</span>
                            <div class="text-xl font-black text-emerald-600 mt-1">
                                + {{ $stockEntry->quantity }} unités
                            </div>
                        </div>
                    </div>

                    <!-- Action Footer -->
                    <div class="flex justify-end space-x-3 border-t border-gray-100 pt-6">
                        <a href="{{ route('entries.index') }}" class="inline-flex items-center px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition">
                            Fermer
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
