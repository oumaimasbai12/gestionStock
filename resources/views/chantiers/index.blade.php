<x-app-layout>
    @section('title', 'Chantiers')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="page-title">{{ __('Chantiers') }}</h2>
                <p class="page-subtitle">Gérez les sites de construction pour l'affectation du stock.</p>
            </div>
            <a href="{{ route('chantiers.create') }}" class="btn-primary text-cream px-4 py-2 text-sm font-semibold">
                Nouveau Chantier
            </a>
        </div>
    </x-slot>

    <div class="app-page">
        <div class="app-container">
            @if(session('success'))
                <div class="app-alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-6 p-4 bg-accent/10 border-l-4 border-accent text-ink text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="app-card">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="app-table-head">
                                <th class="py-4 px-6">Nom du chantier</th>
                                <th class="py-4 px-6 text-center">Responsables</th>
                                <th class="py-4 px-6 text-center">Entrées</th>
                                <th class="py-4 px-6 text-center">Sorties</th>
                                <th class="py-4 px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-ink/80 divide-y divide-ink/10">
                            @forelse($chantiers as $chantier)
                                <tr class="app-table-row">
                                    <td class="py-4 px-6 font-semibold text-ink">{{ $chantier->name }}</td>
                                    <td class="py-4 px-6 text-center">{{ $chantier->users_count }}</td>
                                    <td class="py-4 px-6 text-center">{{ $chantier->entries_count }}</td>
                                    <td class="py-4 px-6 text-center">{{ $chantier->exits_count }}</td>
                                    <td class="py-4 px-6 text-right space-x-2">
                                        <a href="{{ route('chantiers.edit', $chantier) }}" class="btn-muted text-xs px-3 py-1.5">Modifier</a>
                                        <form action="{{ route('chantiers.destroy', $chantier) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ce chantier ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-outline text-xs px-3 py-1.5">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-ink/50">
                                        Aucun chantier. <a href="{{ route('chantiers.create') }}" class="text-sage font-semibold underline">Créer le premier chantier</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($chantiers->hasPages())
                    <div class="px-6 py-4 bg-sage/10 border-t border-ink/15">{{ $chantiers->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
