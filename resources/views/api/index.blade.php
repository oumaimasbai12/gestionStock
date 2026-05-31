<x-app-layout>
    @section('title', 'API Tokens')
    <x-slot name="header">
        <h2 class="page-title">
            {{ __('API Tokens') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @livewire('api.api-token-manager')
        </div>
    </div>
</x-app-layout>
