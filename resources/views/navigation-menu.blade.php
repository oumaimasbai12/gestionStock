<nav x-data="{ open: false }" class="bg-cream border-b-2 border-ink/15">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-mark class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @if(auth()->user()->hasRole('admin'))
                        <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                            {{ __('Tableau de Bord') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('users.index') }}" :active="request()->routeIs('users.index')">
                            {{ __('Utilisateurs') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('customers.index') }}" :active="request()->routeIs('customers.index')">
                            {{ __('Clients') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('products.index') }}" :active="request()->routeIs('products.index')">
                            {{ __('Produits BTP') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('suppliers.index') }}" :active="request()->routeIs('suppliers.index')">
                            {{ __('Fournisseurs') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('entries.index') }}" :active="request()->routeIs('entries.index')">
                            {{ __('Entrées Stock') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('exits.index') }}" :active="request()->routeIs('exits.index')">
                            {{ __('Sorties Stock') }}
                        </x-nav-link>
                    @elseif(auth()->user()->hasRole('storekeeper'))
                        <x-nav-link href="{{ route('products.index') }}" :active="request()->routeIs('products.index')">
                            {{ __('Produits BTP') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('entries.index') }}" :active="request()->routeIs('entries.index')">
                            {{ __('Entrées Stock') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('exits.index') }}" :active="request()->routeIs('exits.index')">
                            {{ __('Sorties Stock') }}
                        </x-nav-link>
                    @elseif(auth()->user()->hasRole('site_manager'))
                        <x-nav-link href="{{ route('entries.index') }}" :active="request()->routeIs('entries.index')">
                            {{ __('Entrées Stock') }}
                        </x-nav-link>
                        <x-nav-link href="{{ route('exits.index') }}" :active="request()->routeIs('exits.index')">
                            {{ __('Sorties Stock') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @if(auth()->user()->hasRole('admin'))
                        @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
                        <!-- Notifications -->
                        <div class="ms-3 relative" x-data="{ open: false }">
                            <button
                                type="button"
                                @click="open = !open"
                                class="relative inline-flex items-center justify-center w-10 h-10 rounded-md border-2 border-ink/15 text-ink/70 hover:text-ink hover:border-ink/30 hover:bg-ink/5 focus:outline-none transition"
                                aria-label="Notifications{{ $unreadCount > 0 ? ' ('.$unreadCount.' non lues)' : '' }}"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                @if($unreadCount > 0)
                                    <span class="app-notify-badge absolute -top-1 -right-1">
                                        {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                    </span>
                                @endif
                            </button>

                            <div x-show="open" @click.away="open = false" x-cloak class="origin-top-right absolute right-0 mt-2 w-80 rounded-md bg-cream border-2 border-ink/15 z-50">
                                <div class="p-3 border-b-2 border-ink/15 flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span class="text-xs font-bold text-ink uppercase tracking-wider">Notifications</span>
                                        @if($unreadCount > 0)
                                            <span class="app-notify-badge">
                                                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                            </span>
                                        @endif
                                    </div>
                                    @if($unreadCount > 0)
                                        <form action="{{ route('notifications.read-all') }}" method="POST" class="shrink-0">
                                            @csrf
                                            <button type="submit" class="text-xs font-semibold text-sage hover:text-sage/80 whitespace-nowrap">Tout marquer lu</button>
                                        </form>
                                    @endif
                                </div>
                                <div class="max-h-80 overflow-y-auto">
                                    @forelse(auth()->user()->notifications->take(10) as $notification)
                                        @php $data = $notification->data; @endphp
                                        <a href="{{ $data['url'] ?? '#' }}" 
                                           @click="
                                               @if(!$notification->read_at)
                                                   fetch('{{ route('notifications.read', $notification->id) }}', {method: 'PATCH', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}})
                                               @endif
                                               open = false
                                           "
                                           class="flex items-start gap-3 px-4 py-3 hover:bg-sage/10 transition border-b border-ink/10 last:border-0 {{ $notification->read_at ? 'opacity-60' : 'bg-accent/10' }}">
                                            <div class="shrink-0 mt-0.5">
                                                @if(($data['type'] ?? '') === 'overdue_invoice')
                                                    <div class="w-8 h-8 rounded-full bg-accent/20 flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    </div>
                                                @else
                                                    <div class="w-8 h-8 rounded-full bg-accent/20 flex items-center justify-center">
                                                        <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="text-xs font-bold text-ink">{{ $data['title'] ?? '' }}</div>
                                                <div class="text-xs text-ink/70 mt-0.5">{{ $data['message'] ?? '' }}</div>
                                                <div class="text-[10px] text-ink/50 mt-1">{{ $notification->created_at->diffForHumans() }}</div>
                                            </div>
                                        </a>
                                    @empty
                                        <div class="text-center py-8 text-ink/50 text-sm">Aucune notification</div>
                                    @endforelse
                                </div>
                                @if(auth()->user()->notifications->count() > 10)
                                    <div class="border-t border-ink/15 p-2 text-center">
                                        <span class="text-xs text-ink/50">+ {{ auth()->user()->notifications->count() - 10 }} autres</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                <!-- Teams Dropdown -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="60">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-ink/70 bg-cream hover:text-ink focus:outline-none focus:bg-sage/10 active:bg-sage/10 transition ease-in-out duration-150">
                                        {{ Auth::user()->currentTeam->name }}

                                        <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </button>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <div class="w-60">
                                    <!-- Team Management -->
                                    <div class="block px-4 py-2 text-xs text-ink/50">
                                        {{ __('Manage Team') }}
                                    </div>

                                    <!-- Team Settings -->
                                    <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                        {{ __('Team Settings') }}
                                    </x-dropdown-link>

                                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                        <x-dropdown-link href="{{ route('teams.create') }}">
                                            {{ __('Create New Team') }}
                                        </x-dropdown-link>
                                    @endcan

                                    <!-- Team Switcher -->
                                    @if (Auth::user()->allTeams()->count() > 1)
                                        <div class="border-t border-ink/20"></div>

                                        <div class="block px-4 py-2 text-xs text-ink/50">
                                            {{ __('Switch Teams') }}
                                        </div>

                                        @foreach (Auth::user()->allTeams() as $team)
                                            <x-switchable-team :team="$team" />
                                        @endforeach
                                    @endif
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif

                <!-- Settings Dropdown -->
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <span class="inline-flex rounded-md">
                                <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-ink/70 bg-cream hover:text-ink focus:outline-none focus:bg-sage/10 active:bg-sage/10 transition ease-in-out duration-150">
                                    {{ Auth::user()->name }}

                                    <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </button>
                            </span>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-ink/50">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-ink/20"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <x-dropdown-link href="{{ route('logout') }}"
                                         @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-ink/50 hover:text-ink/70 hover:bg-ink/5 focus:outline-none focus:bg-ink/5 focus:text-ink/70 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @if(auth()->user()->hasRole('admin'))
                <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                    {{ __('Tableau de Bord') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('users.index') }}" :active="request()->routeIs('users.index')">
                    {{ __('Utilisateurs') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('customers.index') }}" :active="request()->routeIs('customers.index')">
                    {{ __('Clients') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('products.index') }}" :active="request()->routeIs('products.index')">
                    {{ __('Produits BTP') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('suppliers.index') }}" :active="request()->routeIs('suppliers.index')">
                    {{ __('Fournisseurs') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('entries.index') }}" :active="request()->routeIs('entries.index')">
                    {{ __('Entrées Stock') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('exits.index') }}" :active="request()->routeIs('exits.index')">
                    {{ __('Sorties Stock') }}
                </x-responsive-nav-link>
            @elseif(auth()->user()->hasRole('storekeeper'))
                <x-responsive-nav-link href="{{ route('products.index') }}" :active="request()->routeIs('products.index')">
                    {{ __('Produits BTP') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('entries.index') }}" :active="request()->routeIs('entries.index')">
                    {{ __('Entrées Stock') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('exits.index') }}" :active="request()->routeIs('exits.index')">
                    {{ __('Sorties Stock') }}
                </x-responsive-nav-link>
            @elseif(auth()->user()->hasRole('site_manager'))
                <x-responsive-nav-link href="{{ route('entries.index') }}" :active="request()->routeIs('entries.index')">
                    {{ __('Entrées Stock') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('exits.index') }}" :active="request()->routeIs('exits.index')">
                    {{ __('Sorties Stock') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-ink/20">
            <div class="flex items-center px-4">
                <div>
                    <div class="font-medium text-base text-ink">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-ink/70">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                        {{ __('API Tokens') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <x-responsive-nav-link href="{{ route('logout') }}"
                                   @click.prevent="$root.submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>

                <!-- Team Management -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="border-t border-ink/20"></div>

                    <div class="block px-4 py-2 text-xs text-ink/50">
                        {{ __('Manage Team') }}
                    </div>

                    <!-- Team Settings -->
                    <x-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}" :active="request()->routeIs('teams.show')">
                        {{ __('Team Settings') }}
                    </x-responsive-nav-link>

                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                        <x-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                            {{ __('Create New Team') }}
                        </x-responsive-nav-link>
                    @endcan

                    <!-- Team Switcher -->
                    @if (Auth::user()->allTeams()->count() > 1)
                        <div class="border-t border-ink/20"></div>

                        <div class="block px-4 py-2 text-xs text-ink/50">
                            {{ __('Switch Teams') }}
                        </div>

                        @foreach (Auth::user()->allTeams() as $team)
                            <x-switchable-team :team="$team" component="responsive-nav-link" />
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
    </div>
</nav>
