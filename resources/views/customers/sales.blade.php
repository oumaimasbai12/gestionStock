<x-app-layout>
    @section('title', 'Ventes Client')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="page-title">
                    {{ __('Historique des ventes') }}
                </h2>
                <p class="page-subtitle">{{ $customer->name }}</p>
            </div>
            <a href="{{ route('customers.index') }}" class="btn-muted">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Retour aux clients
            </a>
        </div>
    </x-slot>

    <div class="app-page">
        <div class="app-container">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="app-panel">
                    <span class="text-xs text-ink/50 font-bold uppercase tracking-wider">Total des ventes</span>
                    <div class="text-xl font-black text-ink mt-1">{{ $exits->total() }}</div>
                </div>
                <div class="app-panel">
                    <span class="text-xs text-ink/50 font-bold uppercase tracking-wider">Chiffre d'affaires</span>
                    <div class="text-xl font-black text-ink mt-1">
                        {{ number_format($exits->sum(fn($e) => $e->quantity * $e->unit_price), 0, ',', ' ') }} <span class="text-sm font-bold text-ink/70">MAD</span>
                    </div>
                </div>
                <div class="app-panel">
                    <span class="text-xs text-ink/50 font-bold uppercase tracking-wider">Montant payé</span>
                    <div class="text-xl font-black text-sage mt-1">
                        {{ number_format($exits->sum('paid_amount'), 0, ',', ' ') }} <span class="text-sm font-bold text-sage/70">MAD</span>
                    </div>
                </div>
                <div class="app-panel">
                    <span class="text-xs text-ink/50 font-bold uppercase tracking-wider">Solde restant</span>
                    @php
                        $balance = $exits->sum(fn($e) => ($e->quantity * $e->unit_price) - $e->paid_amount);
                    @endphp
                    <div class="text-xl font-black {{ $balance > 0 ? 'text-accent' : 'text-sage' }} mt-1">
                        {{ number_format($balance, 0, ',', ' ') }} <span class="text-sm font-bold {{ $balance > 0 ? 'text-accent/70' : 'text-sage/70' }}">MAD</span>
                    </div>
                </div>
            </div>

            <div class="app-card">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="app-table-head">
                                <th class="py-4 px-6">{{ __('Réf') }}</th>
                                <th class="py-4 px-6">{{ __('Produit') }}</th>
                                <th class="py-4 px-6">{{ __('Chantier') }}</th>
                                <th class="py-4 px-6 text-right">{{ __('Qté') }}</th>
                                <th class="py-4 px-6 text-right">{{ __('Total') }}</th>
                                <th class="py-4 px-6 text-right">{{ __('Payé') }}</th>
                                <th class="py-4 px-6 text-center">{{ __('Statut') }}</th>
                                <th class="py-4 px-6 text-right">{{ __('Date') }}</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-ink/80 divide-y divide-ink/10">
                            @forelse($exits as $exit)
                            <tr class="app-table-row">
                                <td class="py-4 px-6 font-semibold text-ink">{{ $exit->document ?? 'N/A' }}</td>
                                <td class="py-4 px-6">{{ $exit->product->name ?? 'N/A' }}</td>
                                <td class="py-4 px-6">{{ $exit->chantier->name ?? '-' }}</td>
                                <td class="py-4 px-6 text-right">{{ $exit->quantity }}</td>
                                <td class="py-4 px-6 text-right font-semibold">{{ number_format($exit->quantity * $exit->unit_price, 0, ',', ' ') }} MAD</td>
                                <td class="py-4 px-6 text-right">{{ number_format($exit->paid_amount, 0, ',', ' ') }} MAD</td>
                                <td class="py-4 px-6 text-center">
                                    @if($exit->payment_status == 'paid')
                                        <span class="app-badge bg-sage/15 border-sage/30">Payée</span>
                                    @elseif($exit->payment_status == 'partial')
                                        <span class="app-badge">Partielle</span>
                                    @else
                                        <span class="app-badge">Impayée</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right text-ink/70">{{ $exit->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-ink/50 text-sm">Aucune vente enregistrée pour ce client.</td>
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
