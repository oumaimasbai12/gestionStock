<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Stocket - Système de Gestion des Stocks</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <!-- Tailwind CSS con Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <!-- Swiper Carousel CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
    <style>
        /* Estilos opcionales para Swiper */
        .swiper { width: 100%; height: 100%; }
        .swiper-slide { text-align: center; font-size: 1rem; background: #fff; display: flex; justify-content: center; align-items: center; }
    </style>
</head>
<body class="antialiased bg-gray-100">
<!-- HERO SECTION -->
<div class="relative min-h-screen flex items-center justify-center">
    <!-- Imagen de fondo -->
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://media.licdn.com/dms/image/v2/D5612AQF7PAA7acNSww/article-cover_image-shrink_720_1280/article-cover_image-shrink_720_1280/0/1689770107117?e=2147483647&v=beta&t=Skb3W6Tiu0B5tMRjqpliNCr0aYPO0M8hDOwjOexpta0');"></div>
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black opacity-60"></div>
    <!-- Contenido central -->
    <div class="relative z-10 text-center p-6 max-w-2xl" data-aos="fade-up">
        <h1 class="text-4xl sm:text-5xl font-bold text-white mb-4">Stocket</h1>
        <p class="text-xl sm:text-2xl text-gray-200 mb-8">
            Gestion efficace des stocks pour les produits, fournisseurs, clients et mouvements de stock.
        </p>
        <div class="space-x-4">
            @if (Route::has('login'))
            @auth
            <a href="{{ url('/dashboard') }}" class="inline-block bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-3 px-6 rounded-lg" data-aos="fade-right">
                Tableau de bord
            </a>
            @else
            <a href="{{ route('login') }}" class="inline-block bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-3 px-6 rounded-lg" data-aos="fade-right">
                Se connecter
            </a>
            @if (Route::has('register'))
            <a href="{{ route('register') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 px-6 rounded-lg" data-aos="fade-left">
                S'inscrire
            </a>
            @endif
            @endauth
            @endif
        </div>
    </div>
</div>

<!-- SECCIÓN "¿QUÉ ES STOCKET?" - Diseño de dos columnas -->
<section class="max-w-7xl mx-auto p-6 mt-12" data-aos="fade-up">
    <div class="flex flex-col md:flex-row items-center">
        <div class="w-full md:w-1/2 p-4">
            <x-application-mark class="rounded-lg shadow-xl" />
        </div>
        <div class="w-full md:w-1/2 p-4">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Qu'est-ce que Stocket ?</h2>
            <p class="text-lg text-gray-600">
                Stocket est un système complet de gestion des stocks développé sous Laravel, conçu pour vous aider à gérer vos produits, fournisseurs, clients et mouvements de stock de manière simple et efficace. Avec des tableaux de bord en temps réel et des graphiques interactifs, le suivi de vos stocks n'a jamais été aussi facile.
            </p>
        </div>
    </div>
</section>

<!-- SECCIÓN DE "¿CÓMO FUNCIONA?" (Timeline refinado con números y con íconos) -->
<section class="max-w-7xl mx-auto p-6 mt-12" data-aos="fade-up">
    <h2 class="text-3xl font-bold text-gray-800 text-center mb-12">Comment ça marche ?</h2>
    <div class="relative wrap overflow-hidden p-10">
        <!-- Línea vertical central -->
        <div class="absolute border-l-2 border-gray-300 h-full left-1/2 transform -translate-x-1/2"></div>
        <!-- Elemento 1 -->
        <div class="mb-8 flex justify-between items-center w-full">
            <div class="w-5/12"></div>
            <div class="w-5/12 relative px-6 py-4 bg-white rounded-lg shadow-lg">
                <!-- Número en círculo -->
                <div class="absolute -left-8 top-1/2 transform -translate-y-1/2 bg-indigo-500 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold">
                    1
                </div>
                <div class="flex items-center justify-center mb-2">
                    <!-- Ícono de configuración -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 01-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09a1.65 1.65 0 00-1-1.51 1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09a1.65 1.65 0 001.51-1 1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06a1.65 1.65 0 001.82.33h.09a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51h.09a1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06a1.65 1.65 0 00-.33 1.82v.09a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z" />
                    </svg>
                    <h3 class="text-xl font-bold text-gray-800">Configuration</h3>
                </div>
                <p class="text-gray-600">
                    Définissez vos produits, fournisseurs et clients pour commencer à gérer vos stocks.
                </p>
            </div>
        </div>
        <!-- Elemento 2 -->
        <div class="mb-8 flex justify-between items-center w-full">
            <div class="w-5/12 relative px-6 py-4 bg-white rounded-lg shadow-lg">
                <div class="absolute -right-8 top-1/2 transform -translate-y-1/2 bg-green-500 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold">
                    2
                </div>
                <div class="flex items-center justify-center mb-2">
                    <!-- Ícono de movimiento -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m0 0l-4-4m4 4l4-4" />
                    </svg>
                    <h3 class="text-xl font-bold text-gray-800">Mouvements</h3>
                </div>
                <p class="text-gray-600">
                    Enregistrez rapidement et précisément les entrées et sorties de stock.
                </p>
            </div>
            <div class="w-5/12"></div>
        </div>
        <!-- Elemento 3 -->
        <div class="mb-8 flex justify-between items-center w-full">
            <div class="w-5/12"></div>
            <div class="w-5/12 relative px-6 py-4 bg-white rounded-lg shadow-lg">
                <div class="absolute -left-8 top-1/2 transform -translate-y-1/2 bg-red-500 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold">
                    3
                </div>
                <div class="flex items-center justify-center mb-2">
                    <!-- Ícono de análisis -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 11V4m0 7a2 2 0 104 0M6 18v-2a4 4 0 014-4h.5" />
                    </svg>
                    <h3 class="text-xl font-bold text-gray-800">Analyses</h3>
                </div>
                <p class="text-gray-600">
                    Consultez des rapports et des tableaux de bord interactifs pour prendre des décisions basées sur les données.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- SECCIÓN DE CASOS DE USO (mejorada) -->
<section class="max-w-7xl mx-auto p-6 mt-12" data-aos="fade-up">
    <h2 class="text-3xl font-bold text-gray-800 text-center mb-8">Cas d'utilisation</h2>
    <p class="mt-4 text-lg text-gray-600 text-center">
        Stocket s'adapte aux différents besoins et tailles d'entreprise.
    </p>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-8">
        <!-- Caso 1: Restaurantes -->
        <div class="bg-white rounded-lg shadow-lg p-6 text-center transform hover:scale-105 transition duration-300">
            <div class="flex justify-center mb-4">
                <!-- Ícono de restaurante -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 3v18M16 3v18M4 9h16" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Restaurants</h3>
            <p class="text-gray-600">Optimisez la gestion de vos stocks et réduisez les pertes dans votre restaurant.</p>
        </div>
        <!-- Caso 2: Comercios -->
        <div class="bg-white rounded-lg shadow-lg p-6 text-center transform hover:scale-105 transition duration-300">
            <div class="flex justify-center mb-4">
                <!-- Ícono de comercio (bolsa de compras) -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V3a1 1 0 00-1-1H9a1 1 0 00-1 1v8M5 11h14l-1 9H6l-1-9z" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Commerces</h3>
            <p class="text-gray-600">Maintenez un inventaire à jour et améliorez l'expérience d'achat de vos clients.</p>
        </div>
        <!-- Caso 3: Distribuidores -->
        <div class="bg-white rounded-lg shadow-lg p-6 text-center transform hover:scale-105 transition duration-300">
            <div class="flex justify-center mb-4">
                <!-- Ícono de distribuidor (camión) -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 13h4l2 5h10l2-5h4M5 13V7a2 2 0 012-2h6a2 2 0 012 2v6" />
                    <circle cx="7" cy="19" r="2" stroke="currentColor" stroke-width="2" />
                    <circle cx="17" cy="19" r="2" stroke="currentColor" stroke-width="2" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Distributeurs</h3>
            <p class="text-gray-600">Contrôlez le flux de produits et optimisez la logistique de distribution.</p>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="mt-16 py-6 bg-gray-200" data-aos="fade-up">
    <div class="max-w-7xl mx-auto text-center text-gray-700">
        <p class="text-sm">&copy; {{ date('Y') }} Stocket. <span class="text-indigo-500">cawtoz</span></p>
    </div>
</footer>

<!-- Scripts: AOS y Swiper JS -->
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        once: true,
    });
</script>
<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
<script>
    var swiper = new Swiper('.swiper-container', {
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
    });
</script>
</body>
</html>
