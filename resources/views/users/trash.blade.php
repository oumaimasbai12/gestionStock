<x-app-layout>
    @section('title', 'Utilisateurs - Corbeille')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-ink">
            {{ __('Utilisateurs supprimés') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="app-container">
            <!-- Contenedor principal -->
            <div class="bg-cream border-2 border-ink/15 overflow-hidden sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full w-full divide-y divide-ink/10">
                            <thead class="bg-sage/10">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-ink/70 uppercase tracking-wider">{{ __('Nom') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-ink/70 uppercase tracking-wider">{{ __('Email') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-ink/70 uppercase tracking-wider">{{ __('Actions') }}</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-ink/10">
                            @foreach($users as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-1">
                                    <a href="{{ route('users.restore', $user->id) }}" class="inline-flex items-center px-3 py-1 bg-accent text-white rounded-md hover:bg-accent/90">
                                        <!-- Icono de restaurar (flecha curva a la izquierda) -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v6h6M4 10l6-6a9 9 0 11-3 14.32" />
                                        </svg>
                                        {{ __('Restaurer') }}
                                    </a>
                                    <form action="{{ route('users.forceDelete', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Êtes-vous sûr de supprimer définitivement?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1 bg-accent text-white rounded-md hover:bg-ink">
                                            <!-- Icono de eliminar (papelera) -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a1 1 0 011 1v2H9V4a1 1 0 011-1z"/>
                                            </svg>
                                            {{ __('Supprimer définitivement') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fin contenedor principal -->
        </div>
    </div>
</x-app-layout>
