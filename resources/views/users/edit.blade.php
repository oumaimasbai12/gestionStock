<x-app-layout>
    @section('title', 'Modifier Utilisateur')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="page-title">{{ __('Modifier l\'utilisateur') }}</h2>
            <a href="{{ route('users.index') }}" class="btn-muted">Retour</a>
        </div>
    </x-slot>

    <div class="app-page">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="app-card p-8">
                <form action="{{ route('users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @php $currentRole = old('role', $user->roles->first()?->name); @endphp
                    <div class="space-y-5">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-ink mb-1">{{ __('Nom') }}</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="app-input mt-1">
                            @error('name')<span class="text-accent text-xs mt-1 block">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-semibold text-ink mb-1">{{ __('Email') }}</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="app-input mt-1">
                            @error('email')<span class="text-accent text-xs mt-1 block">{{ $message }}</span>@enderror
                        </div>
                        @if(!$user->hasRole('admin'))
                            <div>
                                <label for="role" class="block text-sm font-semibold text-ink mb-1">{{ __('Rôle') }}</label>
                                <select name="role" id="role" class="app-select mt-1">
                                    @foreach($roles as $role)
                                        @php
                                            $label = match($role->name) {
                                                'storekeeper' => 'Magasinier',
                                                'site_manager' => 'Responsable de chantier',
                                                default => ucfirst($role->name),
                                            };
                                        @endphp
                                        <option value="{{ $role->name }}" {{ $currentRole === $role->name ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('role')<span class="text-accent text-xs mt-1 block">{{ $message }}</span>@enderror
                            </div>
                            <div id="chantier-field" class="{{ $currentRole === 'site_manager' ? '' : 'hidden' }}">
                                <label for="chantier_id" class="block text-sm font-semibold text-ink mb-1">Chantier assigné</label>
                                <select name="chantier_id" id="chantier_id" class="app-select mt-1">
                                    <option value="">Sélectionner un chantier...</option>
                                    @foreach($chantiers as $chantier)
                                        <option value="{{ $chantier->id }}" {{ old('chantier_id', $user->chantier_id) == $chantier->id ? 'selected' : '' }}>{{ $chantier->name }}</option>
                                    @endforeach
                                </select>
                                @error('chantier_id')<span class="text-accent text-xs mt-1 block">{{ $message }}</span>@enderror
                            </div>
                        @endif
                    </div>
                    <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-ink/15">
                        <a href="{{ route('users.index') }}" class="btn-muted">Annuler</a>
                        <button type="submit" class="btn-primary text-cream">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if(!$user->hasRole('admin'))
    <script>
        document.getElementById('role')?.addEventListener('change', function () {
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
    @endif
</x-app-layout>
