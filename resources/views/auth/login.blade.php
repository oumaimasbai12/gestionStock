<x-guest-layout>
    @section('title', 'Connexion')
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-6 text-center">
            <h1 class="text-lg font-semibold text-ink">Connexion</h1>
            <p class="text-sm text-ink/70 mt-1">Accédez à votre espace Stocket</p>
        </div>

        <x-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-sage">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-label for="email" value="Adresse e-mail" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nom@exemple.com" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="Mot de passe" />
                <x-password-input
                    id="password"
                    class="block mt-1 w-full"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                />
            </div>

            <div class="flex items-center justify-end mt-6">
                <x-button>
                    Se connecter
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
