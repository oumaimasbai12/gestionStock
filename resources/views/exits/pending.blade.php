<x-app-layout>
    @section('title', 'Impayés')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Factures Impayées') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Ventes en statut Non Payé ou Partiel.</p>
            </div>
            <div>
                <a href="{{ route('exits.index') }}" class="inline-flex items-center bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-200 px-4 py-2 rounded-xl text-sm font-medium transition space-x-1.5">
                    <span>Toutes les sorties</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl flex items-center space-x-3 shadow-sm">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-gray-400 text-xs uppercase tracking-wider font-semibold">
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
                        <tbody class="text-sm text-gray-600 divide-y divide-gray-50">
                            @forelse($exits as $exit)
                                <tr class="hover:bg-gray-50/80 transition duration-150">
                                    <td class="py-4 px-6">
                                        <div class="font-semibold text-gray-900">{{ $exit->created_at->format('d/m/Y') }}</div>
                                        <div class="text-[11px] text-gray-400">{{ $exit->created_at->format('H:i') }}</div>
                                    </td>
                                    <td class="py-4 px-6 font-semibold text-gray-900">
                                        {{ optional($exit->customer)->name ?? 'N/A' }}
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="font-medium">{{ optional($exit->product)->name ?? 'Produit Supprimé' }}</div>
                                        <div class="text-xs text-gray-400">{{ optional($exit->product)->category ?? '' }}</div>
                                    </td>
                                    <td class="py-4 px-6 text-right font-bold text-gray-900">
                                        {{ number_format($exit->quantity * $exit->unit_price, 2, ',', ' ') }} DH
                                    </td>
                                    <td class="py-4 px-6 text-right font-bold text-emerald-600">
                                        {{ number_format($exit->paid_amount, 2, ',', ' ') }} DH
                                    </td>
                                    <td class="py-4 px-6 text-right font-black text-red-500">
                                        {{ number_format($exit->amount_due, 2, ',', ' ') }} DH
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        @if($exit->payment_status == 'partial')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-100">Partiel</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-100">Non Payé</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <form action="{{ route('exits.mark-paid', $exit) }}" method="POST" onsubmit="return confirm('Marquer cette vente comme entièrement payée ?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-100 px-3 py-1.5 rounded-xl text-xs font-bold transition space-x-1">
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
                                    <td colspan="8" class="py-16 text-center text-gray-400">
                                        <div class="flex flex-col items-center justify-center space-y-2">
                                            <svg class="w-12 h-12 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                        {{ $exits->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
