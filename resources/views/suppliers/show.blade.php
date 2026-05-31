<x-app-layout>
    @section('title', 'Détails Fournisseur')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Détail du Fournisseur') }}
            </h2>
            <a href="{{ route('suppliers.index') }}" class="inline-flex items-center bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl text-sm font-medium transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Retour aux fournisseurs</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-8">
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <span class="text-xs text-gray-400 font-medium uppercase tracking-wider">{{ __('NIT') }}</span>
                            <div class="text-base font-bold text-gray-900 mt-1">{{ $supplier->nit }}</div>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <span class="text-xs text-gray-400 font-medium uppercase tracking-wider">{{ __('Nom') }}</span>
                            <div class="text-base font-bold text-gray-900 mt-1">{{ $supplier->name }}</div>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <span class="text-xs text-gray-400 font-medium uppercase tracking-wider">{{ __('Téléphone') }}</span>
                            <div class="text-base font-bold text-gray-900 mt-1">{{ $supplier->phone }}</div>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <span class="text-xs text-gray-400 font-medium uppercase tracking-wider">{{ __('Email') }}</span>
                            <div class="text-base font-bold text-gray-900 mt-1">{{ $supplier->email }}</div>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 md:col-span-2">
                            <span class="text-xs text-gray-400 font-medium uppercase tracking-wider">{{ __('Adresse') }}</span>
                            <div class="text-base font-bold text-gray-900 mt-1">{{ $supplier->address }}</div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 border-t border-gray-100 pt-6">
                        <a href="{{ route('suppliers.index') }}" class="inline-flex items-center px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition">
                            Fermer
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>