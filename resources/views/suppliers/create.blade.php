<x-app-layout>
    @section('title', 'Nouveau Fournisseur')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="page-title">
                {{ __('Créer Fournisseur') }}
            </h2>
            <a href="{{ route('suppliers.index') }}" class="inline-flex items-center btn-muted px-4 py-2 rounded-md text-sm font-medium transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Retour aux fournisseurs</span>
            </a>
        </div>
    </x-slot>

    <div class="app-page">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="app-card p-8">
                <form action="{{ route('suppliers.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="nit" class="block text-sm font-semibold text-ink mb-1">{{ __('NIT') }}</label>
                            <input type="text" name="nit" id="nit" value="{{ old('nit') }}" required class="mt-1 app-input">
                            @error('nit')
                                <span class="text-accent text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="name" class="block text-sm font-semibold text-ink mb-1">{{ __('Nom') }}</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required class="mt-1 app-input">
                            @error('name')
                                <span class="text-accent text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-ink mb-1">{{ __('Téléphone') }}</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required class="mt-1 app-input">
                            @error('phone')
                                <span class="text-accent text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-semibold text-ink mb-1">{{ __('Email') }}</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required class="mt-1 app-input">
                            @error('email')
                                <span class="text-accent text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-semibold text-ink mb-1">{{ __('Adresse') }}</label>
                            <input type="text" name="address" id="address" value="{{ old('address') }}" required class="mt-1 app-input">
                            @error('address')
                                <span class="text-accent text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 border-t border-ink/15 pt-6">
                        <a href="{{ route('suppliers.index') }}" class="inline-flex items-center px-5 py-2.5 btn-muted font-semibold rounded-md text-sm transition">
                            Annuler
                        </a>
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 btn-primary text-cream font-semibold rounded-md text-sm transition">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>