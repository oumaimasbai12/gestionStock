<x-app-layout>
    @section('title', 'Ventes Client')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Historique des ventes') }}
                </h2>
                <p class="text-xs text-gray-400 mt-1 uppercase tracking-wider font-semibold">
                    {{ $customer->name }}
                </p>
            </div>
            <a href="{{ route('customers.index') }}" class="inline-flex items-center bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl text-sm font-medium transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Retour aux clients</span>
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50/50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Customer Summary --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100/80">
                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Total des ventes</span>
                    <div class="text-xl font-black text-gray-900 mt-1">{{ $exits->total() }}</div>
                </div>
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100/80">
                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Chiffre d'affaires</span>
                    <div class="text-xl font-black text-gray-900 mt-1">
                        {{ number_format($exits->sum(fn($e) => $e->quantity * $e->unit_price), 0, ',', ' ') }} <span class="text-sm font-bold text-gray-500">MAD</span>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100/80">
                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Montant payé</span>
                    <div class="text-xl font-black text-emerald-500 mt-1">
                        {{ number_format($exits->sum('paid_amount'), 0, ',', ' ') }} <span class="text-sm font-bold text-emerald-400">MAD</span>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100/80">
                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Solde restant</span>
                    @php
                        $balance = $exits->sum(fn($e) => ($e->quantity * $e->unit_price) - $e->paid_amount);
                    @endphp
                    <div class="text-xl font-black {{ $balance > 0 ? 'text-red-500' : 'text-green-500' }} mt-1">
                        {{ number_format($balance, 0, ',', ' ') }} <span class="text-sm font-bold {{ $balance > 0 ? 'text-red-400' : 'text-green-400' }}">MAD</span>
                    </div>
                </div>
            </div>

            {{-- Sales Table --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Réf') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Produit') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Chantier') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Qté') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Total') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Payé') }}</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Statut') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Date') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($exits as $exit)
                            <tr class="hover:bg-gray-50/50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $exit->document ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $exit->product->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $exit->chantier->name ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right">{{ $exit->quantity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold">{{ number_format($exit->quantity * $exit->unit_price, 0, ',', ' ') }} MAD</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right">{{ number_format($exit->paid_amount, 0, ',', ' ') }} MAD</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($exit->payment_status == 'paid')
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">Payée</span>
                                    @elseif($exit->payment_status == 'partial')
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700">Partielle</span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700">Impayée</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500">{{ $exit->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-400 text-sm">Aucune vente enregistrée pour ce client.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4">
                    {{ $exits->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>