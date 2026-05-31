<x-app-layout>
    @section('title', 'Détails Sortie')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Détails du Bon de Sortie') }}
            </h2>
            <a href="{{ route('exits.index') }}" class="inline-flex items-center bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl text-sm font-medium transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Retour aux sorties</span>
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
                            <span class="text-xl font-bold text-gray-900">{{ $exit->document ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 font-medium uppercase tracking-wider block text-right">Date d'émission</span>
                            <span class="text-sm font-semibold text-gray-700 block text-right">{{ optional($exit->created_at)->format('d/m/Y à H:i') ?? '-' }}</span>
                        </div>
                    </div>

                    <!-- Main Columns Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Product Info -->
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <span class="text-xs text-gray-400 font-medium uppercase tracking-wider">Produit BTP</span>
                            <div class="text-base font-bold text-gray-900 mt-1">{{ optional($exit->product)->name ?? 'Produit Supprimé' }}</div>
                            <div class="text-xs text-gray-400 mt-0.5">Catégorie: {{ optional($exit->product)->category ?? 'N/A' }}</div>
                        </div>

                        <!-- Chantier Info -->
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <span class="text-xs text-gray-400 font-medium uppercase tracking-wider">Affectation Chantier</span>
                            <div class="text-base font-bold text-gray-900 mt-1">
                                {{ $exit->chantier ? '' . $exit->chantier->name : 'Non Affecté à un chantier' }}
                            </div>
                        </div>

                        <!-- Customer Info -->
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <span class="text-xs text-gray-400 font-medium uppercase tracking-wider">Client Destinataire</span>
                            <div class="text-base font-bold text-gray-900 mt-1">
                                {{ $exit->customer ? '' . $exit->customer->name : 'Aucun client' }}
                            </div>
                        </div>

                        <!-- Payment Status -->
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <span class="text-xs text-gray-400 font-medium uppercase tracking-wider block">Statut du Paiement</span>
                            <div class="mt-1">
                                @if($exit->payment_status == 'paid')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        Payé (Entièrement)
                                    </span>
                                @elseif($exit->payment_status == 'partial')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-100">
                                        Paiement Partiel
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-100">
                                        Non Payé
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Financial Summary Panel -->
                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Calcul Financier BI</h4>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Quantité Sortie :</span>
                                <span class="font-bold text-gray-900">{{ $exit->quantity }} unités</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Prix Unitaire appliqué :</span>
                                <span class="font-bold text-gray-900">{{ number_format($exit->unit_price, 2, ',', ' ') }} DH</span>
                            </div>
                            <hr class="border-gray-100">
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Montant Vente Total :</span>
                                <span class="font-extrabold text-gray-900">{{ number_format($exit->quantity * $exit->unit_price, 2, ',', ' ') }} DH</span>
                            </div>
                            <div class="flex justify-between text-sm text-emerald-700">
                                <span>Montant Payé Initialement :</span>
                                <span class="font-extrabold">{{ number_format($exit->paid_amount, 2, ',', ' ') }} DH</span>
                            </div>
                            <hr class="border-gray-100">
                            <div class="flex justify-between text-base font-black text-gray-900">
                                <span>Solde Restant Dû :</span>
                                <span class="text-red-500">{{ number_format(($exit->quantity * $exit->unit_price) - $exit->paid_amount, 2, ',', ' ') }} DH</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Footer -->
                    <div class="flex justify-between items-center border-t border-gray-100 pt-6">
                        <a href="{{ route('exits.index') }}" class="inline-flex items-center px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Fermer
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
