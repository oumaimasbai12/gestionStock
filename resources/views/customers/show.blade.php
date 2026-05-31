<x-app-layout>
    @section('title', 'Détails Client')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Détail du Client') }}
            </h2>
            <a href="{{ route('customers.index') }}" class="inline-flex items-center bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl text-sm font-medium transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Retour aux clients</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-8">
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <span class="text-xs text-gray-400 font-medium uppercase tracking-wider">{{ __('Segment') }}</span>
                            @php
                                $segmentColors = [
                                    'individual' => 'bg-gray-100 text-gray-700',
                                    'artisan' => 'bg-amber-100 text-amber-700',
                                    'entreprise' => 'bg-blue-100 text-blue-700',
                                ];
                            @endphp
                            <div class="mt-1">
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-bold {{ $segmentColors[$customer->customer_type] ?? 'bg-gray-100' }}">
                                    {{ $customer->customer_type }}
                                </span>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <span class="text-xs text-gray-400 font-medium uppercase tracking-wider">{{ __('N° Document') }}</span>
                            <div class="text-base font-bold text-gray-900 mt-1">{{ $customer->document_id }}</div>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <span class="text-xs text-gray-400 font-medium uppercase tracking-wider">{{ __('ICE') }}</span>
                            <div class="text-base font-bold text-gray-900 mt-1">{{ $customer->ice ?? '-' }}</div>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <span class="text-xs text-gray-400 font-medium uppercase tracking-wider">{{ __('Nom') }}</span>
                            <div class="text-base font-bold text-gray-900 mt-1">{{ $customer->name }}</div>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <span class="text-xs text-gray-400 font-medium uppercase tracking-wider">{{ __('Email') }}</span>
                            <div class="text-base font-bold text-gray-900 mt-1">{{ $customer->email }}</div>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <span class="text-xs text-gray-400 font-medium uppercase tracking-wider">{{ __('Téléphone') }}</span>
                            <div class="text-base font-bold text-gray-900 mt-1">{{ $customer->phone }}</div>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 md:col-span-2">
                            <span class="text-xs text-gray-400 font-medium uppercase tracking-wider">{{ __('Adresse') }}</span>
                            <div class="text-base font-bold text-gray-900 mt-1">{{ $customer->address }}</div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 border-t border-gray-100 pt-6">
                        <a href="{{ route('customers.sales', $customer) }}" class="inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl text-sm shadow-sm transition">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            Historique des ventes
                        </a>
                        <a href="{{ route('customers.index') }}" class="inline-flex items-center px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition">
                            Fermer
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>