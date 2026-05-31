<x-app-layout>
    @section('title', 'Détails Sortie')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="page-title">
                {{ __('Détails du Bon de Sortie') }}
            </h2>
            <a href="{{ route('exits.index') }}" class="inline-flex items-center btn-muted px-4 py-2 rounded-md text-sm font-medium transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Retour aux sorties</span>
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
                            <span class="text-xl font-bold text-ink">{{ $exit->document ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-ink/50 font-medium uppercase tracking-wider block text-right">Date d'émission</span>
                            <span class="text-sm font-semibold text-ink block text-right">{{ optional($exit->created_at)->format('d/m/Y à H:i') ?? '-' }}</span>
                        </div>
                    </div>

                    <!-- Main Columns Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Product Info -->
                        <div class="bg-sage/10 rounded-md p-4 border border-ink/15">
                            <span class="text-xs text-ink/50 font-medium uppercase tracking-wider">Produit BTP</span>
                            <div class="text-base font-bold text-ink mt-1">{{ optional($exit->product)->name ?? 'Produit Supprimé' }}</div>
                            <div class="text-xs text-ink/50 mt-0.5">Catégorie: {{ optional($exit->product)->category ?? 'N/A' }}</div>
                        </div>

                        <!-- Chantier Info -->
                        <div class="bg-sage/10 rounded-md p-4 border border-ink/15">
                            <span class="text-xs text-ink/50 font-medium uppercase tracking-wider">Affectation Chantier</span>
                            <div class="text-base font-bold text-ink mt-1">
                                {{ $exit->chantier ? '' . $exit->chantier->name : 'Non Affecté à un chantier' }}
                            </div>
                        </div>

                        <!-- Customer Info -->
                        <div class="bg-sage/10 rounded-md p-4 border border-ink/15">
                            <span class="text-xs text-ink/50 font-medium uppercase tracking-wider">Client Destinataire</span>
                            <div class="text-base font-bold text-ink mt-1">
                                {{ $exit->customer ? '' . $exit->customer->name : 'Aucun client' }}
                            </div>
                        </div>

                        <!-- Payment Status -->
                        <div class="bg-sage/10 rounded-md p-4 border border-ink/15">
                            <span class="text-xs text-ink/50 font-medium uppercase tracking-wider block">Statut du Paiement</span>
                            <div class="mt-1">
                                @if($exit->payment_status == 'paid')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-sage/15 text-ink border border-sage/30">
                                        Payé (Entièrement)
                                    </span>
                                @elseif($exit->payment_status == 'partial')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-accent/15 text-ink border border-accent/30">
                                        Paiement Partiel
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-accent/15 text-ink border border-accent/30">
                                        Non Payé
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Financial Summary Panel -->
                    <div class="bg-sage/10 rounded-lg p-6 border border-ink/15">
                        <h4 class="text-xs font-bold text-ink/50 uppercase tracking-wider mb-4">Calcul Financier BI</h4>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm text-ink/80">
                                <span>Quantité Sortie :</span>
                                <span class="font-bold text-ink">{{ $exit->quantity }} unités</span>
                            </div>
                            <div class="flex justify-between text-sm text-ink/80">
                                <span>Prix Unitaire appliqué :</span>
                                <span class="font-bold text-ink">{{ number_format($exit->unit_price, 2, ',', ' ') }} DH</span>
                            </div>
                            <hr class="border-ink/15">
                            <div class="flex justify-between text-sm text-ink/80">
                                <span>Montant Vente Total :</span>
                                <span class="font-extrabold text-ink">{{ number_format($exit->quantity * $exit->unit_price, 2, ',', ' ') }} DH</span>
                            </div>
                            <div class="flex justify-between text-sm text-ink">
                                <span>Montant Payé Initialement :</span>
                                <span class="font-extrabold">{{ number_format($exit->paid_amount, 2, ',', ' ') }} DH</span>
                            </div>
                            <hr class="border-ink/15">
                            <div class="flex justify-between text-base font-black text-ink">
                                <span>Solde Restant Dû :</span>
                                <span class="text-accent">{{ number_format(($exit->quantity * $exit->unit_price) - $exit->paid_amount, 2, ',', ' ') }} DH</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Footer -->
                    <div class="flex justify-between items-center border-t border-ink/15 pt-6">
                        <a href="{{ route('exits.index') }}" class="inline-flex items-center px-5 py-2.5 btn-muted font-semibold rounded-md text-sm transition">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Fermer
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
