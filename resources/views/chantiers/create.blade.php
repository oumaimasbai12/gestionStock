<x-app-layout>
    @section('title', 'Nouveau Chantier')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="page-title">Créer un Chantier</h2>
            <a href="{{ route('chantiers.index') }}" class="btn-muted">Retour</a>
        </div>
    </x-slot>

    <div class="app-page">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="app-card p-8">
                <form action="{{ route('chantiers.store') }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-semibold text-ink mb-1">Nom du chantier</label>
                        <input type="text" name="name" id="name" required value="{{ old('name') }}" placeholder="Ex: Chantier Casablanca — Tour A" class="app-input mt-1">
                        @error('name')<span class="text-accent text-xs mt-1 block">{{ $message }}</span>@enderror
                    </div>
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('chantiers.index') }}" class="btn-muted">Annuler</a>
                        <button type="submit" class="btn-primary text-cream">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
