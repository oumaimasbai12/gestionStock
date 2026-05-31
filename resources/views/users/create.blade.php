<x-app-layout>
    @section('title', 'Nouvel Utilisateur')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="page-title">{{ __('Créer un utilisateur') }}</h2>
            <a href="{{ route('users.index') }}" class="btn-muted">Retour</a>
        </div>
    </x-slot>

    <div class="app-page">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="app-card p-8">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="space-y-5">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-ink mb-1">{{ __('Nom') }}</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required class="app-input mt-1">
                            @error('name')<span class="text-accent text-xs mt-1 block">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-semibold text-ink mb-1">{{ __('Email') }}</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required class="app-input mt-1">
                            @error('email')<span class="text-accent text-xs mt-1 block">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-semibold text-ink mb-1">{{ __('Mot de passe') }}</label>
                            <input type="password" name="password" id="password" required class="app-input mt-1">
                            @error('password')<span class="text-accent text-xs mt-1 block">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-ink mb-1">{{ __('Confirmer le mot de passe') }}</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required class="app-input mt-1">
                        </div>
                        <div>
                            <label for="role" class="block text-sm font-semibold text-ink mb-1">{{ __('Rôle') }}</label>
                            <select name="role" id="role" required class="app-select mt-1">
                                <option value="">{{ __('Sélectionner un rôle...') }}</option>
                                @foreach($roles as $role)
                                    @php
                                        $label = match($role->name) {
                                            'storekeeper' => 'Magasinier',
                                            'site_manager' => 'Responsable de chantier',
                                            default => ucfirst($role->name),
                                        };
                                    @endphp
                                    <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('role')<span class="text-accent text-xs mt-1 block">{{ $message }}</span>@enderror
                        </div>
                        <div id="chantier-field" class="{{ old('role') === 'site_manager' ? '' : 'hidden' }}">
                            <label for="chantier_id" class="block text-sm font-semibold text-ink mb-1">Chantier assigné</label>
                            @if($chantiers->isEmpty())
                                <p class="text-sm text-accent mt-1">
                                    Créez d'abord un chantier dans
                                    <a href="{{ route('chantiers.create') }}" class="underline font-semibold">Chantiers</a>.
                                </p>
                            @else
                                <select name="chantier_id" id="chantier_id" class="app-select mt-1">
                                    <option value="">Sélectionner un chantier...</option>
                                    @foreach($chantiers as $chantier)
                                        <option value="{{ $chantier->id }}" {{ old('chantier_id') == $chantier->id ? 'selected' : '' }}>{{ $chantier->name }}</option>
                                    @endforeach
                                </select>
                            @endif
                            @error('chantier_id')<span class="text-accent text-xs mt-1 block">{{ $message }}</span>@enderror
                            <p class="text-xs text-ink/50 mt-1">Obligatoire pour un responsable de chantier. Plusieurs responsables peuvent partager le même chantier.</p>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-ink/15">
                        <a href="{{ route('users.index') }}" class="btn-muted">Annuler</a>
                        <button type="submit" class="btn-primary text-cream">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('role').addEventListener('change', function () {
            const field = document.getElementById('chantier-field');
            const select = document.getElementById('chantier_id');
            if (this.value === 'site_manager') {
                field.classList.remove('hidden');
                if (select) select.required = true;
            } else {
                field.classList.add('hidden');
                if (select) { select.required = false; select.value = ''; }
            }
        });
    </script>
</x-app-layout>
