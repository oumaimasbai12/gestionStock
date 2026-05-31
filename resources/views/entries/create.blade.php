<x-app-layout>
    @section('title', 'Nouvelle Entrée')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="page-title">
                {{ __('Créer un Bon d\'Entrée de Stock') }}
            </h2>
            <a href="{{ route('entries.index') }}" class="inline-flex items-center btn-muted px-4 py-2 rounded-md text-sm font-medium transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Retour aux entrées</span>
            </a>
        </div>
    </x-slot>

    <div class="app-page">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="app-card p-8">
                <form action="{{ route('entries.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Produit BTP -->
                        <div class="md:col-span-2">
                            <label for="product_id" class="block text-sm font-semibold text-ink mb-1">Produit BTP</label>
                            <select name="product_id" id="product_id" required class="mt-1 app-input">
                                <option value="" disabled selected>Sélectionner un produit</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} (Stock actuel: {{ $product->stock }})
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <span class="text-accent text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Fournisseur -->
                        <div>
                            <label for="supplier_id" class="block text-sm font-semibold text-ink mb-1">Fournisseur</label>
                            <select name="supplier_id" id="supplier_id" required class="mt-1 app-input">
                                <option value="" disabled selected>Sélectionner un fournisseur</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
 {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <span class="text-accent text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Chantier (Only for admin/storekeeper) -->
                        <div>
                            @if(auth()->user()->hasRole('site_manager'))
                                <label class="block text-sm font-semibold text-ink mb-1">Chantier d'Affectation</label>
                                <input type="text" disabled value=" {{ optional(auth()->user()->chantier)->name }}" class="mt-1 block w-full border-ink/25 rounded-md bg-sage/10 text-ink/70 transition duration-150 font-bold">
                                <input type="hidden" name="chantier_id" value="{{ auth()->user()->chantier_id }}">
                            @else
                                <label for="chantier_id" class="block text-sm font-semibold text-ink mb-1">Chantier d'Affectation (Optionnel)</label>
                                <select name="chantier_id" id="chantier_id" class="mt-1 app-input">
                                    <option value="" selected>Dépôt central (Global)</option>
                                    @foreach($chantiers as $chantier)
                                        <option value="{{ $chantier->id }}" {{ old('chantier_id') == $chantier->id ? 'selected' : '' }}>
 {{ $chantier->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                            @error('chantier_id')
                                <span class="text-accent text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Quantité Reçue -->
                        <div>
                            <label for="quantity" class="block text-sm font-semibold text-ink mb-1">Quantité Reçue</label>
                            <input type="number" name="quantity" id="quantity" min="1" required value="{{ old('quantity') }}" class="mt-1 app-input">
                            @error('quantity')
                                <span class="text-accent text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Réf. Document -->
                        <div>
                            <label for="document" class="block text-sm font-semibold text-ink mb-1">Référence Document (ex: Bon de Livraison / BL)</label>
                            <input type="text" name="document" id="document" required value="{{ old('document', 'BL-ENT-' . strtoupper(Str::random(6))) }}" class="mt-1 app-input">
                            @error('document')
                                <span class="text-accent text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 border-t border-ink/15 pt-6">
                        <a href="{{ route('entries.index') }}" class="inline-flex items-center px-5 py-2.5 btn-muted font-semibold rounded-md text-sm transition">
                            Annuler
                        </a>
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 btn-primary text-cream font-semibold rounded-md text-sm transition">
                            Enregistrer l'entrée
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
