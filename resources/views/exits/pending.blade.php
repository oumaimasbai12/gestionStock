<x-app-layout>
    @section('title', 'Impayés')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="page-title">
                    {{ __('Factures Impayées') }}
                </h2>
                <p class="page-subtitle">Ventes en statut Non Payé ou Partiel.</p>
            </div>
            <div>
                <a href="{{ route('exits.index') }}" class="inline-flex items-center bg-sage/10 hover:bg-ink/5 text-ink border border-ink/20 px-4 py-2 rounded-md text-sm font-medium transition space-x-1.5">
                    <span>Toutes les sorties</span>
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
                                <th class="py-4 px-6">Date</th>
                                <th class="py-4 px-6">Client</th>
                                <th class="py-4 px-6">Produit</th>
                                <th class="py-4 px-6 text-right">Montant</th>
                                <th class="py-4 px-6 text-right">Payé</th>
                                <th class="py-4 px-6 text-right">Restant Dû</th>
                                <th class="py-4 px-6 text-center">Statut</th>
                                <th class="py-4 px-6 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-ink/80 divide-y divide-ink/10">
                            @forelse($exits as $exit)
                                <tr class="hover:bg-accent/5 transition duration-150">
                                    <td class="py-4 px-6">
                                        <div class="font-semibold text-ink">{{ $exit->created_at->format('d/m/Y') }}</div>
                                        <div class="text-[11px] text-ink/50">{{ $exit->created_at->format('H:i') }}</div>
                                    </td>
                                    <td class="py-4 px-6 font-semibold text-ink">
                                        {{ optional($exit->customer)->name ?? 'N/A' }}
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="font-medium">{{ optional($exit->product)->name ?? 'Produit Supprimé' }}</div>
                                        <div class="text-xs text-ink/50">{{ optional($exit->product)->category ?? '' }}</div>
                                    </td>
                                    <td class="py-4 px-6 text-right font-bold text-ink">
                                        {{ number_format($exit->quantity * $exit->unit_price, 2, ',', ' ') }} DH
                                    </td>
                                    <td class="py-4 px-6 text-right font-bold text-sage">
                                        {{ number_format($exit->paid_amount, 2, ',', ' ') }} DH
                                    </td>
                                    <td class="py-4 px-6 text-right font-black text-accent">
                                        {{ number_format($exit->amount_due, 2, ',', ' ') }} DH
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        @if($exit->payment_status == 'partial')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-accent/15 text-ink border border-accent/30">Partiel</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-accent/15 text-ink border border-accent/30">Non Payé</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <form action="{{ route('exits.mark-paid', $exit) }}" method="POST" onsubmit="return confirm('Marquer cette vente comme entièrement payée ?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center bg-sage/15 hover:bg-sage/20 text-ink border border-sage/30 px-3 py-1.5 rounded-md text-xs font-bold transition space-x-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>Marquer Payé</span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-16 text-center text-ink/50">
                                        <div class="flex flex-col items-center justify-center space-y-2">
                                            <svg class="w-12 h-12 text-sage/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="text-sm font-medium">Aucune facture impayée. Tout est en règle !</span>
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
