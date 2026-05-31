<x-app-layout>
    @section('title', 'Nouvel Utilisateur')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-ink">
            {{ __('Créer un utilisateur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Contenedor principal -->
            <div class="bg-cream border-2 border-ink/15 overflow-hidden sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="block font-medium text-sm text-ink">{{ __('Nom') }}</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required class="mt-1 block w-full border-ink/25 rounded-md focus:border-accent focus:ring-0">
                            @error('name')
                            <span class="text-accent text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="email" class="block font-medium text-sm text-ink">{{ __('Email') }}</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required class="mt-1 block w-full border-ink/25 rounded-md focus:border-accent focus:ring-0">
                            @error('email')
                            <span class="text-accent text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="password" class="block font-medium text-sm text-ink">{{ __('Mot de passe') }}</label>
                            <input type="password" name="password" id="password" required class="mt-1 block w-full border-ink/25 rounded-md focus:border-accent focus:ring-0">
                            @error('password')
                            <span class="text-accent text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="password_confirmation" class="block font-medium text-sm text-ink">{{ __('Confirmer le mot de passe') }}</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required class="mt-1 block w-full border-ink/25 rounded-md focus:border-accent focus:ring-0">
                        </div>
                        <div class="mb-4">
                            <label for="role" class="block font-medium text-sm text-ink">{{ __('Rôle') }}</label>
                            <select name="role" id="role" required class="mt-1 block w-full border-ink/25 rounded-md focus:border-accent focus:ring-0">
                                <option value="">{{ __('Sélectionner un rôle...') }}</option>
                                @foreach($roles as $role)
                                    @if($role->name !== 'admin')
                                        @php
                                            $label = match($role->name) {
                                                'storekeeper' => 'Magasiner',
                                                'site_manager' => 'Super viseur de chantier',
                                                default => ucfirst($role->name),
                                            };
                                        @endphp
                                    <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>{{ $label }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('role')
                            <span class="text-accent text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex justify-between items-center">
                            <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-sage/80 text-white rounded-md font-semibold hover:bg-ink/80">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                {{ __('Retour') }}
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-accent text-white rounded-md font-semibold hover:bg-accent/90 focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V7l-4-4zM7 17h10M7 13h10M7 9h4m6 8v-8a2 2 0 00-2-2H7" />
                                </svg>
                                {{ __('Enregistrer') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Fin contenedor principal -->
        </div>
    </div>
</x-app-layout>
