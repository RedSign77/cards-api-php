<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <script id="Cookiebot" src="https://consent.cookiebot.com/uc.js" data-cbid="a8ac992a-016f-41db-8929-c9d4c51dc9a9" data-blockingmode="auto" type="text/javascript"></script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-QW8J5RJQ9C"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-QW8J5RJQ9C', {
            cookie_expires: 63072000,
            cookie_update: true,
            session_cookie_expires: 1800
        });
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Cards Forge - Your Customizable Digital Card Collection Hub. Create custom cards, decks, games, and access everything via REST API.">
    <meta name="keywords" content="cards, card games, deck builder, trading cards, card collection, custom cards, REST API">

    <title>Cards Forge - Custom Digital Card Platform</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <!-- Custom styles for card animations and effects -->
    <style>
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .card-flip {
            perspective: 1000px;
        }
        .card-flip-inner {
            transition: transform 0.6s;
            transform-style: preserve-3d;
        }
        .card-flip:hover .card-flip-inner {
            transform: rotateY(180deg);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #1e293b 0%, #334155 50%, #475569 100%);
        }
        .card-glow {
            box-shadow: 0 0 20px rgba(239, 68, 68, 0.3);
        }
        .pulse-glow {
            animation: pulse-glow 2s infinite;
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(239, 68, 68, 0.3); }
            50% { box-shadow: 0 0 30px rgba(239, 68, 68, 0.5); }
        }
    </style>
</head>
<body class="min-h-screen gradient-bg text-white font-sans antialiased">
<!-- Navigation Header -->
<nav class="bg-slate-900/50 backdrop-blur-sm border-b border-slate-700/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <h1 class="text-2xl font-bold text-white">
                        <span class="text-red-500">♠</span> Cards Forge
                    </h1>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <a href="/admin" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                    Admin Panel
                </a>
                <a href="/api/documentation" class="text-slate-300 hover:text-white transition-colors duration-200">
                    API Docs
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Beta Notice Banner -->
@if(config('app_version.status') !== 'stable')
<div class="bg-gradient-to-r from-amber-500 to-orange-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex flex-col md:flex-row items-center justify-center gap-2 text-center">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-bold text-lg">{{ strtoupper(config('app_version.status')) }} VERSION</span>
            </div>
            <span class="hidden md:inline">•</span>
            <p class="text-sm md:text-base">
                This platform is currently in {{ config('app_version.status') }}. New user registration requires supervisor approval before first use.
            </p>
        </div>
    </div>
</div>
@endif

<!-- Hero Section -->
<section class="relative py-20 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 opacity-50"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-5xl md:text-7xl font-bold mb-6">
                    <span class="bg-gradient-to-r from-red-500 to-amber-500 bg-clip-text text-transparent">
                        Cards Forge
                    </span>
            </h1>
            <p class="text-xl md:text-2xl text-slate-300 mb-8 max-w-3xl mx-auto">
                Build your custom card universe: Create cards with images and fields, design card types, assemble decks, set up games, and access everything via REST API.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/admin" class="bg-red-600 hover:bg-red-700 text-white px-8 py-4 rounded-xl text-lg font-semibold transition-all duration-200 transform hover:scale-105 card-glow">
                    Get Started
                </a>
                <a href="/api/documentation" class="bg-slate-700 hover:bg-slate-600 text-white px-8 py-4 rounded-xl text-lg font-semibold transition-all duration-200 transform hover:scale-105">
                    Explore API
                </a>
            </div>
        </div>

        <!-- Floating Card Elements -->
        <div class="absolute top-10 left-10 card-flip">
            <div class="card-flip-inner w-16 h-24 bg-gradient-to-br from-red-600 to-red-800 rounded-lg shadow-lg">
                <div class="absolute inset-0 flex items-center justify-center text-white text-2xl font-bold">♠</div>
            </div>
        </div>
        <div class="absolute top-20 right-20 card-flip">
            <div class="card-flip-inner w-16 h-24 bg-gradient-to-br from-amber-500 to-amber-700 rounded-lg shadow-lg">
                <div class="absolute inset-0 flex items-center justify-center text-white text-2xl font-bold">♦</div>
            </div>
        </div>
        <div class="absolute bottom-10 left-20 card-flip">
            <div class="card-flip-inner w-16 h-24 bg-gradient-to-br from-slate-600 to-slate-800 rounded-lg shadow-lg">
                <div class="absolute inset-0 flex items-center justify-center text-white text-2xl font-bold">♣</div>
            </div>
        </div>
        <div class="absolute bottom-20 right-10 card-flip">
            <div class="card-flip-inner w-16 h-24 bg-gradient-to-br from-red-600 to-red-800 rounded-lg shadow-lg">
                <div class="absolute inset-0 flex items-center justify-center text-white text-2xl font-bold">♥</div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-20 bg-slate-800/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-white mb-4">Platform Features</h2>
            <p class="text-xl text-slate-300 max-w-2xl mx-auto">
                Empower your creativity with full customization and seamless API integration.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- User Authentication -->
            <div class="bg-slate-900/50 backdrop-blur-sm rounded-xl p-6 border border-slate-700/50 card-hover">
                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">User Authentication</h3>
                <p class="text-slate-300">Register and login securely via admin panel or REST API endpoints.</p>
            </div>

            <!-- Custom Cards -->
            <div class="bg-slate-900/50 backdrop-blur-sm rounded-xl p-6 border border-slate-700/50 card-hover">
                <div class="w-12 h-12 bg-red-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">Custom Cards</h3>
                <p class="text-slate-300">Create personalized cards with images, custom fields, and metadata.</p>
            </div>

            <!-- Card Types -->
            <div class="bg-slate-900/50 backdrop-blur-sm rounded-xl p-6 border border-slate-700/50 card-hover">
                <div class="w-12 h-12 bg-amber-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012-2h2a2 2 0 012 2m-6 0v12a2 2 0 002 2h2a2 2 0 002-2V6m-4 0a2 2 0 002 2h2a2 2 0 002-2m-6 0a2 2 0 012-2h2a2 2 0 012 2m4 0V4" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">Custom Card Types</h3>
                <p class="text-slate-300">Define and manage your own card types for ultimate flexibility.</p>
            </div>

            <!-- Deck Building -->
            <div class="bg-slate-900/50 backdrop-blur-sm rounded-xl p-6 border border-slate-700/50 card-hover">
                <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">Deck Building</h3>
                <p class="text-slate-300">Assemble decks by adding your custom cards with ease.</p>
            </div>

            <!-- Game Creation -->
            <div class="bg-slate-900/50 backdrop-blur-sm rounded-xl p-6 border border-slate-700/50 card-hover">
                <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.5a2.5 2.5 0 110 5H9V10z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">Game Creation</h3>
                <p class="text-slate-300">Create games that contain decks composed of your cards.</p>
            </div>

            <!-- REST API -->
            <div class="bg-slate-900/50 backdrop-blur-sm rounded-xl p-6 border border-slate-700/50 card-hover">
                <div class="w-12 h-12 bg-indigo-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">REST API Access</h3>
                <p class="text-slate-300">Perform every action programmatically through our comprehensive REST API.</p>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-white mb-4">Platform Statistics</h2>
            <p class="text-xl text-slate-300">Real-time insights into your card ecosystem.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="bg-slate-900/50 backdrop-blur-sm rounded-xl p-8 border border-slate-700/50 card-hover">
                    <div class="text-4xl font-bold text-red-500 mb-2">{{ $totalCards ?? '1,247' }}</div>
                    <div class="text-slate-300">Total Cards</div>
                </div>
            </div>
            <div class="text-center">
                <div class="bg-slate-900/50 backdrop-blur-sm rounded-xl p-8 border border-slate-700/50 card-hover">
                    <div class="text-4xl font-bold text-amber-500 mb-2">{{ $totalDecks ?? '89' }}</div>
                    <div class="text-slate-300">Active Decks</div>
                </div>
            </div>
            <div class="text-center">
                <div class="bg-slate-900/50 backdrop-blur-sm rounded-xl p-8 border border-slate-700/50 card-hover">
                    <div class="text-4xl font-bold text-green-500 mb-2">{{ $totalGames ?? '12' }}</div>
                    <div class="text-slate-300">Created Games</div>
                </div>
            </div>
            <div class="text-center">
                <div class="bg-slate-900/50 backdrop-blur-sm rounded-xl p-8 border border-slate-700/50 card-hover">
                    <div class="text-4xl font-bold text-blue-500 mb-2">{{ $apiEndpoints ?? '24' }}</div>
                    <div class="text-slate-300">API Endpoints</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Access Section -->
<section class="py-20 bg-slate-800/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-white mb-4">Quick Access</h2>
            <p class="text-xl text-slate-300">Jump directly to the tools and resources you need.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Admin Panel -->
            <div class="bg-slate-900/50 backdrop-blur-sm rounded-xl p-8 border border-slate-700/50 card-hover">
                <div class="w-16 h-16 bg-red-600 rounded-xl flex items-center justify-center mb-6 mx-auto pulse-glow">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-semibold text-white mb-4 text-center">Admin Panel</h3>
                <p class="text-slate-300 mb-6 text-center">Manage users, cards, decks, games, and settings through our intuitive interface.</p>
                <div class="text-center">
                    <a href="/admin" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg transition-colors duration-200 inline-block">
                        Open Admin Panel
                    </a>
                </div>
            </div>

            <!-- API Documentation -->
            <div class="bg-slate-900/50 backdrop-blur-sm rounded-xl p-8 border border-slate-700/50 card-hover">
                <div class="w-16 h-16 bg-blue-600 rounded-xl flex items-center justify-center mb-6 mx-auto">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-semibold text-white mb-4 text-center">API Documentation</h3>
                <p class="text-slate-300 mb-6 text-center">Full documentation for our REST API covering all actions and endpoints.</p>
                <div class="text-center">
                    <a href="/api/documentation" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors duration-200 inline-block">
                        View API Docs
                    </a>
                </div>
            </div>

            <!-- Get Started -->
            <div class="bg-slate-900/50 backdrop-blur-sm rounded-xl p-8 border border-slate-700/50 card-hover">
                <div class="w-16 h-16 bg-green-600 rounded-xl flex items-center justify-center mb-6 mx-auto">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-semibold text-white mb-4 text-center">Get Started</h3>
                <p class="text-slate-300 mb-6 text-center">Start building your card collection today!</p>
                <div class="text-center">
                    <a href="/admin/register" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-colors duration-200 inline-block">
                        Sign Up Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-slate-900 border-t border-slate-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="col-span-1 md:col-span-2">
                <h3 class="text-2xl font-bold text-white mb-4">
                    <span class="text-red-500">♠</span> Cards Forge
                </h3>
                <p class="text-slate-300 mb-4">
                    Customizable digital card platform built with Laravel and Filament.
                    Create, manage, and integrate your card games seamlessly.
                </p>
                <div class="flex space-x-4">
                    <span class="text-2xl">♠</span>
                    <span class="text-2xl text-red-500">♥</span>
                    <span class="text-2xl">♣</span>
                    <span class="text-2xl text-red-500">♦</span>
                </div>
            </div>

            <div>
                <h4 class="text-lg font-semibold text-white mb-4">Platform</h4>
                <ul class="space-y-2">
                    <li><a href="/admin" class="text-slate-300 hover:text-white transition-colors">Admin Panel</a></li>
                    <li><a href="/cards" class="text-slate-300 hover:text-white transition-colors">Manage Cards</a></li>
                    <li><a href="/decks" class="text-slate-300 hover:text-white transition-colors">Deck Builder</a></li>
                    <li><a href="/games" class="text-slate-300 hover:text-white transition-colors">Create Games</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-lg font-semibold text-white mb-4">Developers</h4>
                <ul class="space-y-2">
                    <li><a href="/api/documentation" class="text-slate-300 hover:text-white transition-colors">API Documentation</a></li>
                    <li><a href="/api/examples" class="text-slate-300 hover:text-white transition-colors">Code Examples</a></li>
                    <li><a href="/api/status" class="text-slate-300 hover:text-white transition-colors">API Status</a></li>
                    <li><a href="/support" class="text-slate-300 hover:text-white transition-colors">Support</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-slate-700 mt-8 pt-8 text-center">
            <p class="text-slate-400">
                &copy; {{ date('Y') }} Cards Forge. Created by <a href="https://webtech-solutions.hu/{{ str_replace('_', '-', app()->getLocale()) }}" target="_blank">Webtech-Solutions</a>.
            </p>
        </div>
    </div>
</footer>

<!-- JavaScript for enhanced interactions -->
<script>
    // Add smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // Add loading states for buttons
    document.querySelectorAll('a[href^="/admin"], a[href^="/api"]').forEach(link => {
        link.addEventListener('click', function() {
            this.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Loading...';
        });
    });

    // Add parallax effect to floating cards
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const parallax = document.querySelectorAll('.card-flip');
        const speed = 0.5;

        parallax.forEach(element => {
            const yPos = -(scrolled * speed);
            element.style.transform = `translateY(${yPos}px)`;
        });
    });
</script>
</body>
</html>
