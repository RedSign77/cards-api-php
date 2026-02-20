<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @if(setting('analytics_enabled', true) && setting('cookiebot_id'))
    <script id="Cookiebot" src="https://consent.cookiebot.com/uc.js" data-cbid="{{ setting('cookiebot_id') }}" data-blockingmode="auto" type="text/javascript"></script>
    @endif

    @if(setting('analytics_enabled', true) && setting('gtm_id'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ setting('gtm_id') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ setting('gtm_id') }}', {
            cookie_expires: 63072000,
            cookie_update: true,
            session_cookie_expires: 1800
        });
    </script>
    @endif
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Primary Meta Tags -->
    <title>{{ setting('seo_title', 'Cards Forge - Create Custom Trading Cards & Card Games') }}</title>
    <meta name="title" content="{{ setting('seo_title', 'Cards Forge - Card Creation Platform') }}">
    <meta name="description" content="{{ setting('seo_description', 'Create, design and manage custom trading cards and card games. Build your own card collection with our powerful card forging platform. Buy and sell physical cards in our marketplace.') }}">
    <meta name="keywords" content="create cards, custom trading cards, card game creator, design cards, card forging, trading card maker, custom card games, collectible cards, card marketplace">
    <meta name="author" content="{{ setting('seo_author', 'Webtech-Solutions') }}">
    <meta name="robots" content="index, follow">
    <meta name="language" content="English">
    <meta name="revisit-after" content="7 days">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="{{ setting('seo_title', 'Cards Forge - Create Custom Trading Cards') }}">
    <meta property="og:description" content="Create, design and manage custom trading cards and card games. Powerful card forging platform with marketplace.">
    <meta property="og:image" content="{{ asset(setting('og_image', '/images/og-image.png')) }}">
    <meta property="og:site_name" content="{{ setting('site_name', 'Cards Forge') }}">
    <meta property="og:locale" content="en_US">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url('/') }}">
    <meta property="twitter:title" content="{{ setting('seo_title', 'Cards Forge - Create Custom Trading Cards') }}">
    <meta property="twitter:description" content="Create, design and manage custom trading cards and card games. Powerful card forging platform with marketplace.">
    <meta property="twitter:image" content="{{ asset(setting('twitter_image', '/images/twitter-image.png')) }}">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url('/') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset(setting('favicon_32', '/favicon-32x32.png')) }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset(setting('favicon_16', '/favicon-16x16.png')) }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset(setting('apple_touch_icon', '/apple-touch-icon.png')) }}">
    <link rel="manifest" href="{{ asset('/site.webmanifest') }}">

    <!-- Theme Color -->
    <meta name="theme-color" content="{{ setting('theme_color', '#1e293b') }}">
    <meta name="msapplication-TileColor" content="{{ setting('theme_color', '#1e293b') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Structured Data (JSON-LD) -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "WebSite",
        "name": "{{ setting('site_name', 'Cards Forge') }}",
        "alternateName": "Cards Forge Marketplace",
        "url": "{{ url('/') }}",
        "logo": "{{ asset(setting('logo_url', '/images/logo.png')) }}",
        "description": "Buy, sell and trade physical collectible cards. Verified sellers, automated review system, and secure transactions.",
        "potentialAction": {
            "@@type": "SearchAction",
            "target": "{{ url('/marketplace') }}?search={search_term_string}",
            "query-input": "required name=search_term_string"
        },
        "provider": {
            "@@type": "Organization",
            "name": "{{ setting('company_name', 'Webtech Solutions') }}",
            "url": "{{ setting('company_website', 'https://webtech-solutions.hu') }}"
        }
    }
    </script>

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
                    <a href="/" class="text-2xl font-bold text-white hover:text-slate-200 transition-colors">
                        <span class="text-red-500">♠</span> Cards Forge
                    </a>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('marketplace.index') }}" class="text-slate-300 hover:text-white transition-colors duration-200 font-medium">
                    Browse Marketplace
                </a>
                <a href="{{ route('changelog') }}" class="text-slate-300 hover:text-white transition-colors duration-200 font-medium">
                    Changelog
                </a>
                @auth
                <a href="/admin/physical-cards/create" class="text-slate-300 hover:text-white transition-colors duration-200">
                    Sell Cards
                </a>
                <a href="/admin" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                    Dashboard
                </a>
                @else
                <a href="/admin/login" class="text-slate-300 hover:text-white transition-colors duration-200">
                    Login
                </a>
                <a href="/admin/register" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                    Get Started
                </a>
                @endauth
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
            <p class="text-xl md:text-2xl text-slate-300 mb-4 max-w-3xl mx-auto">
                <strong>Forge Your Own Trading Card Games</strong>
            </p>
            <p class="text-lg md:text-xl text-slate-400 mb-8 max-w-3xl mx-auto">
                Create custom cards, design unique card games, and build complete decks with our powerful card creation platform. Plus, buy and sell physical collectible cards in our marketplace.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/admin" class="bg-red-600 hover:bg-red-700 text-white px-8 py-4 rounded-xl text-lg font-semibold transition-all duration-200 transform hover:scale-105 card-glow">
                    Start Creating Cards
                </a>
                <a href="{{ route('marketplace.index') }}" class="bg-slate-600 hover:bg-slate-700 text-white px-8 py-4 rounded-xl text-lg font-semibold transition-all duration-200 transform hover:scale-105">
                    Browse Marketplace
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

<!-- Marketplace Features Section -->
<section class="py-20 bg-slate-800/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-white mb-4">Powerful Card Creation Platform</h2>
            <p class="text-xl text-slate-300 max-w-2xl mx-auto">
                Create custom cards, design games, manage decks - Plus a trusted marketplace for physical cards
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Create Custom Cards -->
            <div class="bg-slate-900/50 backdrop-blur-sm rounded-xl p-6 border border-slate-700/50 card-hover">
                <div class="w-12 h-12 bg-red-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">Create Custom Cards</h3>
                <p class="text-slate-300">Design unique trading cards with flexible data fields, custom attributes, and image uploads.</p>
            </div>

            <!-- Design Card Games -->
            <div class="bg-slate-900/50 backdrop-blur-sm rounded-xl p-6 border border-slate-700/50 card-hover">
                <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">Design Card Games</h3>
                <p class="text-slate-300">Build complete card game systems with custom card types, rules, and mechanics.</p>
            </div>

            <!-- Manage Decks -->
            <div class="bg-slate-900/50 backdrop-blur-sm rounded-xl p-6 border border-slate-700/50 card-hover">
                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">Manage Decks</h3>
                <p class="text-slate-300">Organize your cards into decks with flexible data storage for strategies and configurations.</p>
            </div>

            <!-- REST API -->
            <div class="bg-slate-900/50 backdrop-blur-sm rounded-xl p-6 border border-slate-700/50 card-hover">
                <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">REST API Access</h3>
                <p class="text-slate-300">Full API support for integrating your card data with external applications and services.</p>
            </div>

            <!-- Marketplace -->
            <div class="bg-slate-900/50 backdrop-blur-sm rounded-xl p-6 border border-slate-700/50 card-hover">
                <div class="w-12 h-12 bg-amber-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">Built-in Marketplace</h3>
                <p class="text-slate-300">Sell physical versions of your cards or buy from other creators in our integrated marketplace.</p>
            </div>

            <!-- Secure Platform -->
            <div class="bg-slate-900/50 backdrop-blur-sm rounded-xl p-6 border border-slate-700/50 card-hover">
                <div class="w-12 h-12 bg-indigo-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">Secure & Reliable</h3>
                <p class="text-slate-300">User authentication, data isolation, and secure access controls protect your creations.</p>
            </div>
        </div>
    </div>
</section>

<!-- Platform Statistics Section -->
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-white mb-4">Platform at a Glance</h2>
            <p class="text-xl text-slate-300">Real-time statistics from our growing creator community</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="bg-slate-900/50 backdrop-blur-sm rounded-xl p-8 border border-slate-700/50 card-hover">
                    <div class="text-4xl font-bold text-red-500 mb-2">{{ $totalCards ?? '0' }}</div>
                    <div class="text-slate-300">Cards Created</div>
                </div>
            </div>
            <div class="text-center">
                <div class="bg-slate-900/50 backdrop-blur-sm rounded-xl p-8 border border-slate-700/50 card-hover">
                    <div class="text-4xl font-bold text-purple-500 mb-2">{{ $totalGames ?? '0' }}</div>
                    <div class="text-slate-300">Card Games Designed</div>
                </div>
            </div>
            <div class="text-center">
                <div class="bg-slate-900/50 backdrop-blur-sm rounded-xl p-8 border border-slate-700/50 card-hover">
                    <div class="text-4xl font-bold text-blue-500 mb-2">{{ $totalDecks ?? '0' }}</div>
                    <div class="text-slate-300">Decks Managed</div>
                </div>
            </div>
            <div class="text-center">
                <div class="bg-slate-900/50 backdrop-blur-sm rounded-xl p-8 border border-slate-700/50 card-hover">
                    <div class="text-4xl font-bold text-green-500 mb-2">{{ $totalUsers ?? '0' }}</div>
                    <div class="text-slate-300">Card Creators</div>
                </div>
            </div>
            <div class="text-center">
                <div class="bg-slate-900/50 backdrop-blur-sm rounded-xl p-8 border border-slate-700/50 card-hover">
                    <div class="text-4xl font-bold text-amber-500 mb-2">{{ $activeListings ?? '0' }}</div>
                    <div class="text-slate-300">Marketplace Listings</div>
                </div>
            </div>
            <div class="text-center">
                <div class="bg-slate-900/50 backdrop-blur-sm rounded-xl p-8 border border-slate-700/50 card-hover">
                    <div class="text-4xl font-bold text-orange-500 mb-2">{{ $cardsAvailable ?? '0' }}</div>
                    <div class="text-slate-300">Physical Cards for Sale</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Listings Section -->
@if(isset($featuredListings) && $featuredListings->isNotEmpty())
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-white mb-4">Featured Listings</h2>
            <p class="text-xl text-slate-300">Recently approved cards from verified sellers</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            @foreach($featuredListings as $card)
            <a href="{{ route('marketplace.show', $card) }}" class="group block">
                <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 overflow-hidden transition-all duration-200 hover:border-red-500 hover:transform hover:-translate-y-1">
                    <!-- Card Image - Fixed height and width -->
                    <div class="relative overflow-hidden" style="width: 100%; height: 400px;">
                        @if($card->image)
                            <img src="{{ Storage::url($card->image) }}"
                                 alt="{{ $card->title }}"
                                 style="width: 100%; height: 400px; object-fit: cover; object-position: center;"
                                 class="group-hover:scale-105 transition-transform duration-200 bg-slate-900">
                        @else
                            <div style="width: 100%; height: 400px;" class="flex items-center justify-center bg-slate-900">
                                <svg class="w-20 h-20 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif

                        <!-- Condition Badge -->
                        <div class="absolute top-3 right-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-900/80 text-white backdrop-blur-sm border border-slate-600">
                                {{ ucfirst($card->condition) }}
                            </span>
                        </div>

                        <!-- Tradeable Badge -->
                        @if($card->tradeable)
                        <div class="absolute top-3 left-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-600/80 text-white backdrop-blur-sm">
                                Tradeable
                            </span>
                        </div>
                        @endif
                    </div>

                    <!-- Card Info -->
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-white mb-1 line-clamp-1 group-hover:text-red-400 transition-colors">
                            {{ $card->title }}
                        </h3>

                        @if($card->set)
                        <p class="text-sm text-slate-400 mb-2">{{ $card->set }}</p>
                        @endif

                        <div class="mt-3 pt-3 border-t border-slate-700">
                            <p class="text-2xl font-bold text-white">
                                @php
                                    $currencySymbols = [
                                        'USD' => '$',
                                        'EUR' => '€',
                                        'GBP' => '£',
                                        'JPY' => '¥',
                                        'CAD' => 'CA$',
                                        'AUD' => 'A$',
                                        'HUF' => 'Ft',
                                    ];
                                    $symbol = $currencySymbols[$card->currency] ?? $card->currency;
                                @endphp
                                {{ number_format($card->price_per_unit, 2) }} {{ $symbol }}
                            </p>
                            <p class="text-xs text-slate-400">{{ $card->quantity }} available</p>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <div class="text-center">
            <a href="{{ route('marketplace.index') }}" class="inline-block bg-red-600 hover:bg-red-700 text-white px-8 py-4 rounded-xl text-lg font-semibold transition-all duration-200 transform hover:scale-105">
                View All Listings
            </a>
        </div>
    </div>
</section>
@endif

<!-- How It Works Section -->
<section class="py-20 bg-slate-800/30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-white mb-4">How It Works</h2>
            <p class="text-xl text-slate-300">Simple steps to start buying and selling collectible cards</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-8">
            <!-- Step 1 -->
            <div class="text-center relative">
                <div class="w-20 h-20 bg-red-600 rounded-full flex items-center justify-center mb-6 mx-auto pulse-glow">
                    <span class="text-3xl font-bold text-white">1</span>
                </div>
                <h3 class="text-xl font-semibold text-white mb-3">List Your Card</h3>
                <p class="text-slate-300">Upload photos, add details, and set your price. Support for all conditions and languages.</p>
                @if(!request()->is('md:*'))
                <div class="hidden md:block absolute top-10 left-full w-full">
                    <svg class="w-full h-0.5" viewBox="0 0 100 2">
                        <line x1="0" y1="1" x2="100" y2="1" stroke="#475569" stroke-width="2" stroke-dasharray="5,5"/>
                    </svg>
                </div>
                @endif
            </div>

            <!-- Step 2 -->
            <div class="text-center relative">
                <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center mb-6 mx-auto">
                    <span class="text-3xl font-bold text-white">2</span>
                </div>
                <h3 class="text-xl font-semibold text-white mb-3">Automated Review</h3>
                <p class="text-slate-300">Our system automatically checks your listing for quality, completeness, and policy compliance.</p>
                @if(!request()->is('md:*'))
                <div class="hidden md:block absolute top-10 left-full w-full">
                    <svg class="w-full h-0.5" viewBox="0 0 100 2">
                        <line x1="0" y1="1" x2="100" y2="1" stroke="#475569" stroke-width="2" stroke-dasharray="5,5"/>
                    </svg>
                </div>
                @endif
            </div>

            <!-- Step 3 -->
            <div class="text-center relative">
                <div class="w-20 h-20 bg-purple-600 rounded-full flex items-center justify-center mb-6 mx-auto">
                    <span class="text-3xl font-bold text-white">3</span>
                </div>
                <h3 class="text-xl font-semibold text-white mb-3">Supervisor Approval</h3>
                <p class="text-slate-300">Our team verifies authenticity and ensures listings meet marketplace standards.</p>
                @if(!request()->is('md:*'))
                <div class="hidden md:block absolute top-10 left-full w-full">
                    <svg class="w-full h-0.5" viewBox="0 0 100 2">
                        <line x1="0" y1="1" x2="100" y2="1" stroke="#475569" stroke-width="2" stroke-dasharray="5,5"/>
                    </svg>
                </div>
                @endif
            </div>

            <!-- Step 4 -->
            <div class="text-center relative">
                <div class="w-20 h-20 bg-green-600 rounded-full flex items-center justify-center mb-6 mx-auto">
                    <span class="text-3xl font-bold text-white">4</span>
                </div>
                <h3 class="text-xl font-semibold text-white mb-3">Go Live</h3>
                <p class="text-slate-300">Your card appears in the marketplace, searchable and visible to thousands of collectors.</p>
                @if(!request()->is('md:*'))
                <div class="hidden md:block absolute top-10 left-full w-full">
                    <svg class="w-full h-0.5" viewBox="0 0 100 2">
                        <line x1="0" y1="1" x2="100" y2="1" stroke="#475569" stroke-width="2" stroke-dasharray="5,5"/>
                    </svg>
                </div>
                @endif
            </div>

            <!-- Step 5 -->
            <div class="text-center">
                <div class="w-20 h-20 bg-amber-600 rounded-full flex items-center justify-center mb-6 mx-auto">
                    <span class="text-3xl font-bold text-white">5</span>
                </div>
                <h3 class="text-xl font-semibold text-white mb-3">Complete Sale</h3>
                <p class="text-slate-300">Connect with buyers, negotiate trades, and complete secure transactions.</p>
            </div>
        </div>

        <div class="text-center mt-12">
            <a href="/admin/register" class="inline-block bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-xl text-lg font-semibold transition-all duration-200 transform hover:scale-105">
                Start Selling Today
            </a>
        </div>
    </div>
</section>

<!-- Quick Access Section -->
<section class="py-20 bg-slate-800/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-white mb-4">Quick Access</h2>
            <p class="text-xl text-slate-300">Jump directly to the marketplace tools you need.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Browse Marketplace -->
            <div class="bg-slate-900/50 backdrop-blur-sm rounded-xl p-8 border border-slate-700/50 card-hover">
                <div class="w-16 h-16 bg-red-600 rounded-xl flex items-center justify-center mb-6 mx-auto pulse-glow">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-semibold text-white mb-4 text-center">Browse Marketplace</h3>
                <p class="text-slate-300 mb-6 text-center">Explore thousands of collectible cards from verified sellers worldwide.</p>
                <div class="text-center">
                    <a href="{{ route('marketplace.index') }}" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg transition-colors duration-200 inline-block">
                        Browse Cards
                    </a>
                </div>
            </div>

            <!-- Sell Your Cards -->
            <div class="bg-slate-900/50 backdrop-blur-sm rounded-xl p-8 border border-slate-700/50 card-hover">
                <div class="w-16 h-16 bg-green-600 rounded-xl flex items-center justify-center mb-6 mx-auto">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-semibold text-white mb-4 text-center">Sell Your Cards</h3>
                <p class="text-slate-300 mb-6 text-center">List your collectible cards for sale or trade with our simple upload process.</p>
                <div class="text-center">
                    @auth
                    <a href="/admin/physical-cards/create" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-colors duration-200 inline-block">
                        Create Listing
                    </a>
                    @else
                    <a href="/admin/register" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-colors duration-200 inline-block">
                        Sign Up to Sell
                    </a>
                    @endauth
                </div>
            </div>

            <!-- My Listings -->
            <div class="bg-slate-900/50 backdrop-blur-sm rounded-xl p-8 border border-slate-700/50 card-hover">
                <div class="w-16 h-16 bg-blue-600 rounded-xl flex items-center justify-center mb-6 mx-auto">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-semibold text-white mb-4 text-center">My Listings</h3>
                <p class="text-slate-300 mb-6 text-center">Manage your card listings, track sales, and update inventory.</p>
                <div class="text-center">
                    @auth
                    <a href="/admin/physical-cards" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors duration-200 inline-block">
                        View My Listings
                    </a>
                    @else
                    <a href="/admin/login" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors duration-200 inline-block">
                        Login to Manage
                    </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</section>

<!-- AI Features Section -->
<section class="py-20 bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-4">
            <span class="inline-flex items-center gap-2 bg-indigo-600/20 border border-indigo-500/30 text-indigo-300 text-xs font-semibold px-4 py-1.5 rounded-full uppercase tracking-widest mb-6">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                Demo · Selected Users Only
            </span>
        </div>
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-white mb-4">
                <span class="bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">AI-Powered Features</span>
            </h2>
            <p class="text-xl text-slate-300 max-w-2xl mx-auto">
                Supercharge your card creation workflow with integrated AI assistance — from generating card data to deep deck analytics.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- AI Card Suggestion -->
            <div class="bg-slate-900/60 backdrop-blur-sm rounded-xl p-6 border border-indigo-700/30 card-hover">
                <div class="w-12 h-12 bg-indigo-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">AI Card Suggestions</h3>
                <p class="text-slate-300 mb-4">Automatically generate card names, descriptions, and ability scores based on your game's style and existing cards.</p>
                <a href="/admin/cards/create" class="text-indigo-400 hover:text-indigo-300 text-sm font-medium transition-colors">Try in Card Editor →</a>
            </div>

            <!-- Ability Re-roll -->
            <div class="bg-slate-900/60 backdrop-blur-sm rounded-xl p-6 border border-purple-700/30 card-hover">
                <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">Ability Re-roll</h3>
                <p class="text-slate-300 mb-4">Refine card abilities with tone control — Aggressive, Defensive, or Support — for perfectly balanced gameplay text.</p>
                <a href="/admin/cards" class="text-purple-400 hover:text-purple-300 text-sm font-medium transition-colors">Open Card Editor →</a>
            </div>

            <!-- Deck Analytics -->
            <div class="bg-slate-900/60 backdrop-blur-sm rounded-xl p-6 border border-blue-700/30 card-hover">
                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">Deck Analytics</h3>
                <p class="text-slate-300 mb-4">Deep synergy analysis, mana curve evaluation, and win-rate prediction for your decks with detailed AI reports.</p>
                <a href="/admin/decks" class="text-blue-400 hover:text-blue-300 text-sm font-medium transition-colors">Analyze Your Decks →</a>
            </div>

            <!-- Card Swap Suggestions -->
            <div class="bg-slate-900/60 backdrop-blur-sm rounded-xl p-6 border border-emerald-700/30 card-hover">
                <div class="w-12 h-12 bg-emerald-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">Smart Card Swap</h3>
                <p class="text-slate-300 mb-4">AI-recommended card substitutions to improve deck balance, with one-click execution directly in the deck editor.</p>
                <a href="/admin/decks" class="text-emerald-400 hover:text-emerald-300 text-sm font-medium transition-colors">Open Deck Editor →</a>
            </div>

            <!-- Coming Soon -->
            <div class="bg-slate-900/40 backdrop-blur-sm rounded-xl p-6 border border-slate-700/30 opacity-70">
                <div class="w-12 h-12 bg-slate-700 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-slate-400 mb-2">More Coming Soon</h3>
                <p class="text-slate-500 mb-4">AI-powered image generation, automated card balancing, and meta analysis features are in development.</p>
                <span class="text-slate-600 text-sm font-medium">In Development</span>
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
                    Buy, sell, and trade physical collectible cards with verified sellers worldwide.
                    Secure transactions, automated reviews, and a thriving collector community.
                </p>
                <div class="flex space-x-4">
                    <span class="text-2xl">♠</span>
                    <span class="text-2xl text-red-500">♥</span>
                    <span class="text-2xl">♣</span>
                    <span class="text-2xl text-red-500">♦</span>
                </div>
            </div>

            <div>
                <h4 class="text-lg font-semibold text-white mb-4">Marketplace</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('marketplace.index') }}" class="text-slate-300 hover:text-white transition-colors">Browse Cards</a></li>
                    <li><a href="/admin/physical-cards/create" class="text-slate-300 hover:text-white transition-colors">Sell Cards</a></li>
                    <li><a href="/admin/physical-cards" class="text-slate-300 hover:text-white transition-colors">My Listings</a></li>
                    <li><a href="/admin" class="text-slate-300 hover:text-white transition-colors">Dashboard</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-lg font-semibold text-white mb-4">Support</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('terms') }}" class="text-slate-300 hover:text-white transition-colors">Terms & Conditions</a></li>
                    <li><a href="{{ route('privacy') }}" class="text-slate-300 hover:text-white transition-colors">Privacy Policy</a></li>
                    <li><a href="/admin/register" class="text-slate-300 hover:text-white transition-colors">Create Account</a></li>
                    <li><a href="/admin/login" class="text-slate-300 hover:text-white transition-colors">Login</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-slate-700 mt-8 pt-8">
            <div class="flex flex-col items-center space-y-4">
                <div class="flex items-center space-x-4 text-sm text-slate-300">
                    <a href="{{ route('terms') }}" class="hover:text-white transition-colors">Terms & Conditions</a>
                    <span class="text-slate-600">•</span>
                    <a href="{{ route('privacy') }}" class="hover:text-white transition-colors">Privacy Policy</a>
                </div>
                <p class="text-slate-400">
                    &copy; {{ date('Y') }} Cards Forge. Created by <a href="https://webtech-solutions.hu/{{ str_replace('_', '-', app()->getLocale()) }}" target="_blank" class="hover:text-white transition-colors">Webtech-Solutions</a>.
                </p>
            </div>
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
