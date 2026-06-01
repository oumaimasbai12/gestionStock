@if(auth()->user()->hasRole('admin'))
    <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
        {{ __('Tableau de Bord') }}
    </x-nav-link>
    <x-nav-link href="{{ route('users.index') }}" :active="request()->routeIs('users.*')">
        {{ __('Utilisateurs') }}
    </x-nav-link>
    <x-nav-link href="{{ route('customers.index') }}" :active="request()->routeIs('customers.*')">
        {{ __('Clients') }}
    </x-nav-link>
    <x-nav-link href="{{ route('products.index') }}" :active="request()->routeIs('products.*')">
        {{ __('Produits BTP') }}
    </x-nav-link>
    <x-nav-link href="{{ route('suppliers.index') }}" :active="request()->routeIs('suppliers.*')">
        {{ __('Fournisseurs') }}
    </x-nav-link>
    <x-nav-link href="{{ route('chantiers.index') }}" :active="request()->routeIs('chantiers.*')">
        {{ __('Chantiers') }}
    </x-nav-link>
    <x-nav-link href="{{ route('entries.index') }}" :active="request()->routeIs('entries.*')">
        {{ __('Entrées Stock') }}
    </x-nav-link>
    <x-nav-link href="{{ route('exits.index') }}" :active="request()->routeIs('exits.*')">
        {{ __('Sorties Stock') }}
    </x-nav-link>
    <x-nav-link href="{{ route('stock-history.index') }}" :active="request()->routeIs('stock-history.*')">
        {{ __('Historique Stock') }}
    </x-nav-link>
@elseif(auth()->user()->hasRole('storekeeper'))
    <x-nav-link href="{{ route('products.index') }}" :active="request()->routeIs('products.*')">
        {{ __('Produits BTP') }}
    </x-nav-link>
    <x-nav-link href="{{ route('entries.index') }}" :active="request()->routeIs('entries.*')">
        {{ __('Entrées Stock') }}
    </x-nav-link>
    <x-nav-link href="{{ route('exits.index') }}" :active="request()->routeIs('exits.*')">
        {{ __('Sorties Stock') }}
    </x-nav-link>
    <x-nav-link href="{{ route('stock-history.index') }}" :active="request()->routeIs('stock-history.*')">
        {{ __('Historique Stock') }}
    </x-nav-link>
@elseif(auth()->user()->hasRole('site_manager'))
    <x-nav-link href="{{ route('entries.index') }}" :active="request()->routeIs('entries.*')">
        {{ __('Entrées Stock') }}
    </x-nav-link>
    <x-nav-link href="{{ route('exits.index') }}" :active="request()->routeIs('exits.*')">
        {{ __('Sorties Stock') }}
    </x-nav-link>
    <x-nav-link href="{{ route('stock-history.index') }}" :active="request()->routeIs('stock-history.*')">
        {{ __('Historique Stock') }}
    </x-nav-link>
@endif
