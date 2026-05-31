<x-app-layout>
    @section('title', 'Fournisseurs')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Fournisseurs') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Gérez vos fournisseurs de matériaux BTP.</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('suppliers.trash') }}" class="inline-flex items-center bg-gray-50 hover:bg-gray-100 text-red-600 border border-gray-200 px-4 py-2 rounded-xl text-sm font-medium transition space-x-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a1 1 0 011 1v2H9V4a1 1 0 011-1z"/>
                    </svg>
                    <span>Poubelle</span>
                </a>
                <a href="{{ route('suppliers.create') }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-sm transition space-x-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Nouveau Fournisseur</span>
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
                                <td class="py-4 px-6 font-semibold text-gray-900">{{ $supplier->name }}</td>
                                <td class="py-4 px-6">{{ $supplier->phone }}</td>
                                <td class="py-4 px-6 text-gray-500">{{ $supplier->email }}</td>
                                <td class="py-4 px-6 text-right">
                                    <div class="inline-flex items-center space-x-2">
                                        <a href="{{ route('suppliers.show', $supplier) }}" class="inline-flex items-center bg-gray-50 hover:bg-gray-100 text-gray-700 border border-gray-200 px-3 py-1.5 rounded-xl text-xs font-semibold shadow-sm transition space-x-1" title="Voir">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            <span>Voir</span>
                                        </a>
                                        <a href="{{ route('suppliers.edit', $supplier) }}" class="inline-flex items-center bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-100 px-3 py-1.5 rounded-xl text-xs font-semibold shadow-sm transition space-x-1" title="Modifier">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-2.036a5.5 5.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                            <span>Modifier</span>
                                        </a>
                                        <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce fournisseur ?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center bg-red-50 hover:bg-red-100 text-red-700 border border-red-100 px-3 py-1.5 rounded-xl text-xs font-semibold shadow-sm transition space-x-1" title="Supprimer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a1 1 0 011 1v2H9V4a1 1 0 011-1z"/>
                                                </svg>
                                                <span>Supprimer</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-gray-400">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        <span class="text-sm font-medium">Aucun fournisseur enregistré.</span>
                                    </div>
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