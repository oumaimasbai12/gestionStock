<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Modifier le Produit BTP') }}
            </h2>
            <a href="{{ route('products.index') }}" class="inline-flex items-center bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl text-sm font-medium transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Retour au catalogue</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-8">
                <form action="{{ route('products.update', $product) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Désignation -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Désignation & Matériau</label>
                            <input type="text" name="name" id="name" required value="{{ old('name', $product->name) }}" class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                            @error('name')
                                <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Catégorie -->
                        <div>
                            <label for="category" class="block text-sm font-semibold text-gray-700 mb-1">Catégorie</label>
                            <select name="category" id="category" required class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                                <option value="" disabled>Sélectionner une catégorie</option>
                                <option value="Liants Hydrauliques" {{ old('category', $product->category) == 'Liants Hydrauliques' ? 'selected' : '' }}>Liants Hydrauliques</option>
                                <option value="Acier & Ferraillage" {{ old('category', $product->category) == 'Acier & Ferraillage' ? 'selected' : '' }}>Acier & Ferraillage</option>
                                <option value="Granulats & Sables" {{ old('category', $product->category) == 'Granulats & Sables' ? 'selected' : '' }}>Granulats & Sables</option>
                                <option value="Maçonnerie & Blocs" {{ old('category', $product->category) == 'Maçonnerie & Blocs' ? 'selected' : '' }}>Maçonnerie & Blocs</option>
                                <option value="Peintures & Enduits" {{ old('category', $product->category) == 'Peintures & Enduits' ? 'selected' : '' }}>Peintures & Enduits</option>
                                <option value="Électricité" {{ old('category', $product->category) == 'Électricité' ? 'selected' : '' }}>Électricité</option>
                                <option value="Outillage" {{ old('category', $product->category) == 'Outillage' ? 'selected' : '' }}>Outillage</option>
                                <option value="Divers" {{ old('category', $product->category) == 'Divers' ? 'selected' : '' }}>Divers</option>
                            </select>
                            @error('category')
                                <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Prix d'achat -->
                        <div>
                            <label for="purchase_price" class="block text-sm font-semibold text-gray-700 mb-1">Prix d'Achat (DH)</label>
                            <input type="number" name="purchase_price" id="purchase_price" step="0.01" min="0" required value="{{ old('purchase_price', $product->purchase_price) }}" class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                            @error('purchase_price')
                                <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Stock -->
                        <div class="md:col-span-2">
                            <label for="stock" class="block text-sm font-semibold text-gray-700 mb-1">Stock</label>
                            <input type="number" name="stock" id="stock" min="0" required value="{{ old('stock', $product->stock) }}" class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                            @error('stock')
                                <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                            <textarea name="description" id="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 border-t border-gray-100 pt-6">
                        <a href="{{ route('products.index') }}" class="inline-flex items-center px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition">
                            Annuler
                        </a>
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm shadow-sm transition">
                            Mettre à jour le produit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
