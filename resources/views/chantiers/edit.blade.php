<x-app-layout>
    @section('title', 'Modifier Chantier')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="page-title">Modifier le Chantier</h2>
            <a href="{{ route('chantiers.index') }}" class="btn-muted">Retour</a>
        </div>
    </x-slot>

    <div class="app-page">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="app-card p-8">
                <form action="{{ route('chantiers.update', $chantier) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-semibold text-ink mb-1">Nom du chantier</label>
                        <input type="text" name="name" id="name" required value="{{ old('name', $chantier->name) }}" class="app-input mt-1">
                        @error('name')<span class="text-accent text-xs mt-1 block">{{ $message }}</span>@enderror
                    </div>
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('chantiers.index') }}" class="btn-muted">Annuler</a>
                        <button type="submit" class="btn-primary text-cream">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
