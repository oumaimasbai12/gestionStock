<x-app-layout>
    @section('title', 'Fournisseurs')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="page-title">
                    {{ __('Fournisseurs') }}
                </h2>
                <p class="page-subtitle">Gérez vos fournisseurs de matériaux BTP.</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('suppliers.trash') }}" class="inline-flex items-center bg-sage/10 hover:bg-ink/5 text-accent border border-ink/20 px-4 py-2 rounded-md text-sm font-medium transition space-x-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a1 1 0 011 1v2H9V4a1 1 0 011-1z"/>
                    </svg>
                    <span>Poubelle</span>
                </a>
                <a href="{{ route('suppliers.create') }}" class="inline-flex items-center btn-primary text-cream px-4 py-2 rounded-md text-sm font-semibold transition space-x-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Nouveau Fournisseur</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="app-page">
        <div class="app-container">

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
                                <th class="py-4 px-6">{{ __('NIT') }}</th>
                                <th class="py-4 px-6">{{ __('Nom') }}</th>
                                <th class="py-4 px-6">{{ __('Téléphone') }}</th>
                                <th class="py-4 px-6">{{ __('Email') }}</th>
                                <th class="py-4 px-6 text-right">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-ink/80 divide-y divide-ink/10">
                            @forelse($suppliers as $supplier)
                            <tr class="hover:bg-accent/5 transition duration-150">
                                <td class="py-4 px-6 font-medium text-ink">{{ $supplier->nit }}</td>
                                <td class="py-4 px-6 font-semibold text-ink">{{ $supplier->name }}</td>
                                <td class="py-4 px-6">{{ $supplier->phone }}</td>
                                <td class="py-4 px-6 text-ink/70">{{ $supplier->email }}</td>
                                <td class="py-4 px-6 text-right">
                                    <div class="inline-flex items-center space-x-2">
                                        <a href="{{ route('suppliers.show', $supplier) }}" class="inline-flex items-center bg-sage/10 hover:bg-ink/5 text-ink border border-ink/20 px-3 py-1.5 rounded-md text-xs font-semibold transition space-x-1" title="Voir">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            <span>Voir</span>
                                        </a>
                                        <a href="{{ route('suppliers.edit', $supplier) }}" class="inline-flex items-center bg-accent/15 hover:bg-accent/20 text-ink border border-accent/30 px-3 py-1.5 rounded-md text-xs font-semibold transition space-x-1" title="Modifier">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-2.036a5.5 5.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                            </svg>
                                            <span>Modifier</span>
                                        </a>
                                        <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce fournisseur ?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center bg-accent/15 hover:bg-accent/20 text-ink border border-accent/30 px-3 py-1.5 rounded-md text-xs font-semibold transition space-x-1" title="Supprimer">
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
                                <td colspan="5" class="py-12 text-center text-ink/50">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <svg class="w-10 h-10 text-ink/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        <span class="text-sm font-medium">Aucun fournisseur enregistré.</span>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($suppliers, 'links'))
                    <div class="px-6 py-4 bg-sage/10 border-t border-ink/15">
                        {{ $suppliers->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>