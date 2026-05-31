<x-app-layout>
    @section('title', 'Détails Utilisateur')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-ink">
            {{ __('Détail de l\'utilisateur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-cream border-2 border-ink/15 overflow-hidden sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-4">
                        <p class="text-ink"><strong>{{ __('Nom:') }}</strong> {{ $user->name }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-ink"><strong>{{ __('Email:') }}</strong> {{ $user->email }}</p>
                    </div>

                    <div class="mb-4">
                        <p class="text-ink"><strong>{{ __('Rôle:') }}</strong></p>
                        <ul class="list-disc list-inside">
                            @foreach($user->roles as $role)
                            @php
                                $label = match($role->name) {
                                    'storekeeper' => 'Magasiner',
                                    'site_manager' => 'Super viseur de chantier',
                                    'admin' => 'Admin',
                                    default => ucfirst($role->name),
                                };
                            @endphp
                            <li class="text-sage font-semibold">{{ $label }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @if($user->hasRole('site_manager'))
                    <div class="mb-4">
                        <p class="text-ink"><strong>{{ __('Chantier:') }}</strong> {{ $user->chantier?->name ?? 'Non assigné' }}</p>
                    </div>
                    @endif
                    <div class="flex justify-start mt-4">
                        <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-sage/80 text-white rounded-md font-semibold hover:bg-ink/80">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            {{ __('Retour') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
