<div>
    <!-- Mobile overlay -->
    <div
        x-show="sidebarOpen"
        x-transition:enter="transition-opacity ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="sidebarOpen = false"
        class="app-sidebar-overlay"
        x-cloak
    ></div>

    <!-- Sidebar -->
    <aside
        class="app-sidebar fixed inset-y-0 left-0 z-40 flex h-dvh w-64 flex-col bg-cream"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    >
        <!-- Logo -->
        <div class="app-brand-bar">
            <a href="{{ auth()->user()->hasRole('admin') ? route('dashboard') : route('entries.index') }}" wire:navigate wire:navigate.hover @click="sidebarOpen = false" class="flex items-center gap-3">
                <x-application-mark class="h-9 w-auto" />
                <span class="font-bold text-ink text-lg leading-tight">Stocket</span>
            </a>
        </div>

        <!-- Nav links -->
        <nav class="flex-1 overflow-y-auto px-2 py-4 space-y-0.5" @click="if ($event.target.closest('a')) sidebarOpen = false">
            @include('layouts.partials.sidebar-links')
        </nav>

        <!-- Footer: notifications + user -->
        <div class="shrink-0 border-t-2 border-ink/15 p-3 space-y-2">
            @if(auth()->user()->hasRole('admin'))
                @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
                <div class="relative" x-data="{ notifOpen: false }">
                    <button
                        type="button"
                        @click="notifOpen = !notifOpen"
                        class="flex w-full items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium text-ink/80 hover:bg-ink/5 hover:text-ink transition"
                    >
                        <span class="relative inline-flex">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            @if($unreadCount > 0)
                                <span class="app-notify-badge absolute -top-1 -right-1">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
                            @endif
                        </span>
                        <span>Notifications</span>
                    </button>
                    <div
                        x-show="notifOpen"
                        @click.away="notifOpen = false"
                        x-cloak
                        class="absolute bottom-full left-0 right-0 mb-2 mx-1 rounded-md bg-cream border-2 border-ink/15 z-50 max-h-72 overflow-hidden flex flex-col"
                    >
                        <div class="p-3 border-b-2 border-ink/15 flex items-center justify-between gap-2">
                            <span class="text-xs font-bold text-ink uppercase">Notifications</span>
                            @if($unreadCount > 0)
                                <form action="{{ route('notifications.read-all') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs font-semibold text-accent">Tout lu</button>
                                </form>
                            @endif
                        </div>
                        <div class="overflow-y-auto flex-1">
                            @forelse(auth()->user()->notifications->take(8) as $notification)
                                @php $data = $notification->data; @endphp
                                <a href="{{ $data['url'] ?? '#' }}" class="block px-3 py-2.5 text-xs border-b border-ink/10 hover:bg-accent/10 {{ $notification->read_at ? 'opacity-60' : 'bg-accent/15' }}">
                                    <div class="font-bold text-ink">{{ $data['title'] ?? '' }}</div>
                                    <div class="text-ink/70 mt-0.5 line-clamp-2">{{ $data['message'] ?? '' }}</div>
                                </a>
                            @empty
                                <p class="p-4 text-center text-ink/50 text-xs">Aucune notification</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif

            <div class="relative" x-data="{ userOpen: false }">
                <button
                    type="button"
                    @click="userOpen = !userOpen"
                    class="flex w-full items-center gap-3 px-3 py-2.5 rounded-md text-sm hover:bg-ink/5 transition text-left"
                >
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-md bg-accent/25 text-ink font-bold text-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </span>
                    <span class="min-w-0 flex-1">
                        <span class="block text-sm font-semibold text-ink truncate leading-tight">{{ Auth::user()->name }}</span>
                        @if(Auth::user()->hasRole('site_manager'))
                            <span class="block text-xs font-medium text-ink/75 truncate mt-0.5">{{ Auth::user()->chantier?->name ?? 'Sans chantier' }}</span>
                        @else
                            <span class="block text-xs font-medium text-ink/75 truncate mt-0.5">{{ Auth::user()->email }}</span>
                        @endif
                    </span>
                    <svg class="w-4 h-4 shrink-0 text-ink/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div
                    x-show="userOpen"
                    @click.away="userOpen = false"
                    x-cloak
                    class="absolute bottom-full left-0 right-0 mb-1 mx-1 py-1 rounded-md bg-cream border-2 border-ink/15 z-50"
                >
                    <x-dropdown-link href="{{ route('profile.show') }}">{{ __('Profile') }}</x-dropdown-link>
                    @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                        <x-dropdown-link href="{{ route('api-tokens.index') }}">{{ __('API Tokens') }}</x-dropdown-link>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf
                        <x-dropdown-link href="{{ route('logout') }}" data-no-navigate @click.prevent="$root.submit();">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <!-- Mobile top bar -->
    <header class="app-mobile-bar">
        <button
            type="button"
            @click="sidebarOpen = !sidebarOpen"
            class="inline-flex items-center justify-center w-10 h-10 rounded-md border-2 border-ink/15 text-ink hover:bg-ink/5"
            aria-label="Menu"
        >
            <svg x-show="!sidebarOpen" class="w-5 h-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <svg x-show="sidebarOpen" x-cloak class="w-5 h-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <a href="{{ auth()->user()->hasRole('admin') ? route('dashboard') : route('entries.index') }}" wire:navigate class="flex items-center gap-2">
            <x-application-mark class="h-8 w-auto" />
            <span class="font-bold text-ink">Stocket</span>
        </a>
    </header>
</div>
