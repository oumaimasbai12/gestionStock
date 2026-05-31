<x-app-layout>
    @section('title', 'Nouveau Client')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Créer Client') }}
            </h2>
            <a href="{{ route('customers.index') }}" class="inline-flex items-center bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl text-sm font-medium transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Retour aux clients</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50/50">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-8">
                <form action="{{ route('customers.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="customer_type" class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Segment') }}</label>
                            <select name="customer_type" id="customer_type" required class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                                <option value="individual" {{ old('customer_type') == 'individual' ? 'selected' : '' }}>INDIVIDUAL</option>
                                <option value="artisan" {{ old('customer_type') == 'artisan' ? 'selected' : '' }}>ARTISAN</option>
                                <option value="entreprise" {{ old('customer_type') == 'entreprise' ? 'selected' : '' }}>ENTREPRISE</option>
                            </select>
                            @error('customer_type')
                                <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="document_id" class="block text-sm font-semibold text-gray-700 mb-1">{{ __('N° Document') }}</label>
                            <input type="text" name="document_id" id="document_id" value="{{ old('document_id') }}" required class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                            @error('document_id')
                                <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Nom') }}</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                            @error('name')
                                <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Email') }}</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                            @error('email')
                                <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Téléphone') }}</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                            @error('phone')
                                <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="ice" class="block text-sm font-semibold text-gray-700 mb-1">{{ __('ICE') }} <span id="iceRequired" class="text-red-500 text-xs hidden">(obligatoire pour ENTREPRISE)</span></label>
                            <input type="text" name="ice" id="ice" value="{{ old('ice') }}" class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                            @error('ice')
                                <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-semibold text-gray-700 mb-1">{{ __('Adresse') }}</label>
                            <input type="text" name="address" id="address" value="{{ old('address') }}" required class="mt-1 block w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                            @error('address')
                                <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 border-t border-gray-100 pt-6">
                        <a href="{{ route('customers.index') }}" class="inline-flex items-center px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition">
                            Annuler
                        </a>
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm shadow-sm transition">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const typeSelect = document.getElementById('customer_type');
            const iceInput = document.getElementById('ice');

            function toggleIce() {
                const label = document.getElementById('iceRequired');
                if (typeSelect.value === 'entreprise') {
                    iceInput.required = true;
                    label.classList.remove('hidden');
                } else {
                    iceInput.required = false;
                    label.classList.add('hidden');
                }
            }

            typeSelect.addEventListener('change', toggleIce);
            toggleIce();
        });
    </script>
</x-app-layout>