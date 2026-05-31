<x-app-layout>
    @section('title', 'Nouveau Produit')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="page-title">
                {{ __('Créer un Nouveau Produit BTP') }}
            </h2>
            <a href="{{ route('products.index') }}" class="inline-flex items-center btn-muted px-4 py-2 rounded-md text-sm font-medium transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Retour au catalogue</span>
            </a>
        </div>
    </x-slot>

    <div class="app-page">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="app-card p-8">
                <form action="{{ route('products.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Désignation -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-semibold text-ink mb-1">Désignation & Matériau</label>
                            <input type="text" name="name" id="name" required value="{{ old('name') }}" class="mt-1 app-input">
                            @error('name')
                                <span class="text-accent text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Catégorie -->
                        <div>
                            <label for="category" class="block text-sm font-semibold text-ink mb-1">Catégorie</label>
                            <select name="category" id="category" required class="mt-1 app-input">
                                <option value="" disabled selected>Sélectionner une catégorie</option>
                                <option value="Liants Hydrauliques" {{ old('category') == 'Liants Hydrauliques' ? 'selected' : '' }}>Liants Hydrauliques</option>
                                <option value="Acier & Ferraillage" {{ old('category') == 'Acier & Ferraillage' ? 'selected' : '' }}>Acier & Ferraillage</option>
                                <option value="Granulats & Sables" {{ old('category') == 'Granulats & Sables' ? 'selected' : '' }}>Granulats & Sables</option>
                                <option value="Maçonnerie & Blocs" {{ old('category') == 'Maçonnerie & Blocs' ? 'selected' : '' }}>Maçonnerie & Blocs</option>
                                <option value="Peintures & Enduits" {{ old('category') == 'Peintures & Enduits' ? 'selected' : '' }}>Peintures & Enduits</option>
                                <option value="Électricité" {{ old('category') == 'Électricité' ? 'selected' : '' }}>Électricité</option>
                                <option value="Outillage" {{ old('category') == 'Outillage' ? 'selected' : '' }}>Outillage</option>
                                <option value="Divers" {{ old('category') == 'Divers' ? 'selected' : '' }}>Divers</option>
                            </select>
                            @error('category')
                                <span class="text-accent text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Prix d'achat -->
                        <div>
                            <label for="purchase_price" class="block text-sm font-semibold text-ink mb-1">Prix d'Achat (DH)</label>
                            <input type="number" name="purchase_price" id="purchase_price" step="0.01" min="0" required value="{{ old('purchase_price') }}" class="mt-1 app-input">
                            @error('purchase_price')
                                <span class="text-accent text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Stock initial -->
                        <div class="md:col-span-2">
                            <label for="stock" class="block text-sm font-semibold text-ink mb-1">Stock Initial</label>
                            <input type="number" name="stock" id="stock" min="0" required value="{{ old('stock', 0) }}" class="mt-1 app-input">
                            @error('stock')
                                <span class="text-accent text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-semibold text-ink mb-1">Description</label>
                            <textarea name="description" id="description" rows="4" class="mt-1 app-input">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="text-accent text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 border-t border-ink/15 pt-6">
                        <a href="{{ route('products.index') }}" class="inline-flex items-center px-5 py-2.5 btn-muted font-semibold rounded-md text-sm transition">
                            Annuler
                        </a>
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 btn-primary text-cream font-semibold rounded-md text-sm transition">
                            Enregistrer le produit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
