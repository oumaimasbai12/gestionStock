<x-app-layout>
    @section('title', 'Fournisseurs - Corbeille')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Fournisseurs Supprimés') }}
            </h2>
            <a href="{{ route('suppliers.index') }}" class="inline-flex items-center bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl text-sm font-medium transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Retour aux fournisseurs</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-gray-400 text-xs uppercase tracking-wider font-semibold">
                                <th class="py-4 px-6">{{ __('NIT') }}</th>
                                <th class="py-4 px-6">{{ __('Nom') }}</th>
                                <th class="py-4 px-6">{{ __('Téléphone') }}</th>
                                <th class="py-4 px-6">{{ __('Email') }}</th>
                                <th class="py-4 px-6 text-right">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-600 divide-y divide-gray-50">
                            @forelse($suppliers as $supplier)
                            <tr class="hover:bg-gray-50/80 transition duration-150">
                                <td class="py-4 px-6 font-medium text-gray-900">{{ $supplier->nit }}</td>
                                <td class="py-4 px-6 font-semibold">{{ $supplier->name }}</td>
                                <td class="py-4 px-6">{{ $supplier->phone }}</td>
                                <td class="py-4 px-6 text-gray-500">{{ $supplier->email }}</td>
                                <td class="py-4 px-6 text-right">
                                    <div class="inline-flex items-center space-x-2">
                                        <a href="{{ route('suppliers.restore', $supplier->id) }}" class="inline-flex items-center bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-100 px-3 py-1.5 rounded-xl text-xs font-semibold shadow-sm transition space-x-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v6h6M4 10l6-6a9 9 0 11-3 14.32"/></svg>
                                            <span>Restaurer</span>
                                        </a>
                                        <form action="{{ route('suppliers.forceDelete', $supplier->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de supprimer définitivement ce fournisseur?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center bg-red-50 hover:bg-red-100 text-red-700 border border-red-100 px-3 py-1.5 rounded-xl text-xs font-semibold shadow-sm transition space-x-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a1 1 0 011 1v2H9V4a1 1 0 011-1z"/></svg>
                                                <span>Supprimer définitivement</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-gray-400">
                                    <span class="text-sm font-medium">Aucun fournisseur dans la corbeille.</span>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($suppliers, 'links'))
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                        {{ $suppliers->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>