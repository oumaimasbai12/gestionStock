<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@hasSection('title')@yield('title') - @endif{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div
            class="app-layout"
            x-data="{ sidebarOpen: false }"
            @keydown.escape.window="sidebarOpen = false"
        >
            @livewire('navigation-menu')

            <div class="app-main">
                <div
                    id="app-page-transition-overlay"
                    x-persist="page-transition"
                    class="page-transition-overlay"
                    aria-hidden="true"
                ></div>
                <div id="app-page-content" class="app-page-content">
                    @if (isset($header))
                        <header class="app-header">
                            <div class="app-page-header">
                                <div class="page-header-inner">
                                    {{ $header }}
                                </div>
                            </div>
                        </header>
                    @endif

                    <main>
                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>

        @stack('modals')

        @livewireScripts
        @vite(['resources/js/page-transitions.js'])
    </body>
</html>
