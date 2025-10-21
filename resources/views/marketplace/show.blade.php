<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $card->title }} - Cards Forge Marketplace</title>
    <meta name="description" content="Buy {{ $card->title }} - {{ $card->condition }} condition. {{ $card->set ? $card->set . ' set.' : '' }} Price: ${{ number_format($card->price_per_unit, 2) }}">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <!-- Schema.org Structured Data -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Product",
        "name": "{{ $card->title }}",
        "description": "{{ $card->description ?? 'Physical collectible card - ' . $card->condition . ' condition' }}",
        @if($card->image)
        "image": "{{ Storage::url($card->image) }}",
        @endif
        "offers": {
            "@@type": "Offer",
            "price": "{{ $card->price_per_unit }}",
            "priceCurrency": "{{ $card->price_currency ?? 'USD' }}",
            "availability": "https://schema.org/InStock",
            "seller": {
                "@@type": "Person",
                "name": "{{ $card->user->name }}"
            }
        },
        "condition": "https://schema.org/UsedCondition"
    }
    </script>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white">

<!-- Navigation -->
<nav class="bg-slate-900/50 backdrop-blur-sm border-b border-slate-700/50 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <a href="/" class="text-2xl font-bold text-white hover:text-slate-200 transition-colors">
                    <span class="text-red-500">♠</span> Cards Forge
                </a>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('marketplace.index') }}" class="text-white font-medium">
                    Marketplace
                </a>
                @auth
                <a href="/admin/physical-cards/create" class="text-slate-300 hover:text-white transition-colors">
                    Sell Cards
                </a>
                <a href="/admin" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                    Dashboard
                </a>
                @else
                <a href="/admin/login" class="text-slate-300 hover:text-white transition-colors">
                    Login
                </a>
                <a href="/admin" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                    Get Started
                </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- Breadcrumb -->
<section class="py-4 bg-slate-800/30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li class="inline-flex items-center">
                    <a href="/" class="text-slate-400 hover:text-white transition-colors">
                        Home
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <span class="text-slate-600 mx-2">/</span>
                        <a href="{{ route('marketplace.index') }}" class="text-slate-400 hover:text-white transition-colors">
                            Marketplace
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <span class="text-slate-600 mx-2">/</span>
                        <span class="text-slate-300">{{ Str::limit($card->title, 30) }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
</section>

<!-- Main Content -->
<section class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:grid lg:grid-cols-2 lg:gap-12 mb-16">

            <!-- Card Image -->
            <div class="mb-8 lg:mb-0">
                <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 overflow-hidden sticky top-24">
                    <div class="aspect-[3/4] bg-slate-900 relative">
                        @if($card->image)
                            <img src="{{ Storage::url($card->image) }}"
                                 alt="{{ $card->title }}"
                                 class="w-full h-full object-contain">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-32 h-32 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif

                        <!-- Condition Badge -->
                        <div class="absolute top-4 right-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-slate-900/90 text-white backdrop-blur-sm border border-slate-600">
                                {{ ucfirst($card->condition) }}
                            </span>
                        </div>

                        <!-- Tradeable Badge -->
                        @if($card->tradeable)
                        <div class="absolute top-4 left-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-600/90 text-white backdrop-blur-sm">
                                Open to Trades
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Card Details -->
            <div>
                <div class="mb-6">
                    <h1 class="text-4xl font-bold text-white mb-3">{{ $card->title }}</h1>

                    <div class="flex flex-wrap gap-4 text-slate-300 mb-4">
                        @if($card->set)
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                <span>{{ $card->set }}</span>
                            </div>
                        @endif

                        @if($card->language)
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                                </svg>
                                <span>{{ $card->language }}</span>
                            </div>
                        @endif

                        @if($card->edition)
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                <span>{{ $card->edition }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Price and Quantity -->
                <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl p-6 border border-slate-700/50 mb-6">
                    <div class="flex items-end justify-between mb-4">
                        <div>
                            <p class="text-slate-400 text-sm mb-1">Price per card</p>
                            <p class="text-5xl font-bold text-white">
                                ${{ number_format($card->price_per_unit, 2) }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-slate-400 text-sm mb-1">Available</p>
                            <p class="text-3xl font-bold text-green-500">{{ $card->quantity }}</p>
                        </div>
                    </div>

                    @if($card->quantity > 0)
                        <a href="/admin/login" class="block w-full bg-red-600 hover:bg-red-700 text-white text-center px-6 py-4 rounded-lg transition-colors font-semibold text-lg">
                            Contact Seller
                        </a>
                        <p class="text-xs text-slate-400 text-center mt-2">Login required to contact sellers</p>
                    @else
                        <button disabled class="block w-full bg-slate-700 text-slate-400 text-center px-6 py-4 rounded-lg font-semibold text-lg cursor-not-allowed">
                            Out of Stock
                        </button>
                    @endif
                </div>

                <!-- Description -->
                @if($card->description)
                <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl p-6 border border-slate-700/50 mb-6">
                    <h2 class="text-xl font-bold text-white mb-3">Description</h2>
                    <p class="text-slate-300 leading-relaxed">{{ $card->description }}</p>
                </div>
                @endif

                <!-- Seller Information -->
                <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl p-6 border border-slate-700/50">
                    <h2 class="text-xl font-bold text-white mb-4">Seller Information</h2>

                    <div class="flex items-center mb-4">
                        @if($card->user->avatar_url)
                            <img src="{{ Storage::url($card->user->avatar_url) }}"
                                 alt="{{ $card->user->name }}"
                                 class="w-16 h-16 rounded-full mr-4 border-2 border-slate-600">
                        @else
                            <div class="w-16 h-16 rounded-full mr-4 bg-slate-700 flex items-center justify-center border-2 border-slate-600">
                                <span class="text-2xl font-bold text-slate-300">{{ substr($card->user->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <div>
                            <p class="text-lg font-semibold text-white">{{ $card->user->name }}</p>
                            @if($card->user->seller_location)
                                <p class="text-sm text-slate-400">{{ $card->user->seller_location }}</p>
                            @endif
                        </div>
                    </div>

                    @if($card->user->shipping_options)
                    <div class="border-t border-slate-700 pt-4">
                        <p class="text-sm font-semibold text-slate-300 mb-2">Shipping Information</p>
                        <p class="text-sm text-slate-400">{{ $card->user->shipping_options }}</p>

                        @if($card->user->shipping_price)
                            <p class="text-sm text-slate-300 mt-2">
                                Shipping: ${{ number_format($card->user->shipping_price, 2) }}
                                @if($card->user->delivery_time)
                                    <span class="text-slate-400"> • {{ $card->user->delivery_time }}</span>
                                @endif
                            </p>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Similar Cards -->
        @if($similarCards->isNotEmpty())
        <div class="mt-16">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-bold text-white">Similar Cards</h2>
                <a href="{{ route('marketplace.index', ['search' => $card->set ?? $card->title]) }}"
                   class="text-red-500 hover:text-red-400 transition-colors font-medium">
                    View All →
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($similarCards as $similarCard)
                <a href="{{ route('marketplace.show', $similarCard) }}" class="group block">
                    <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 overflow-hidden transition-all duration-200 hover:border-red-500 hover:transform hover:-translate-y-1">
                        <!-- Card Image -->
                        <div class="aspect-[3/4] bg-slate-900 relative overflow-hidden">
                            @if($similarCard->image)
                                <img src="{{ Storage::url($similarCard->image) }}"
                                     alt="{{ $similarCard->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-16 h-16 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif

                            <!-- Condition Badge -->
                            <div class="absolute top-2 right-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-slate-900/80 text-white backdrop-blur-sm border border-slate-600">
                                    {{ ucfirst($similarCard->condition) }}
                                </span>
                            </div>

                            <!-- Tradeable Badge -->
                            @if($similarCard->tradeable)
                            <div class="absolute top-2 left-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-600/80 text-white backdrop-blur-sm">
                                    Tradeable
                                </span>
                            </div>
                            @endif
                        </div>

                        <!-- Card Info -->
                        <div class="p-4">
                            <h3 class="text-base font-semibold text-white mb-1 line-clamp-1 group-hover:text-red-400 transition-colors">
                                {{ $similarCard->title }}
                            </h3>

                            @if($similarCard->set)
                            <p class="text-xs text-slate-400 mb-2">{{ $similarCard->set }}</p>
                            @endif

                            <div class="flex items-center justify-between mt-2 pt-2 border-t border-slate-700">
                                <p class="text-xl font-bold text-white">
                                    ${{ number_format($similarCard->price_per_unit, 2) }}
                                </p>
                                <p class="text-xs text-slate-400">{{ $similarCard->quantity }} available</p>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

<!-- Footer -->
<footer class="bg-slate-900 border-t border-slate-700 mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="text-center text-slate-400">
            <p>&copy; {{ date('Y') }} Cards Forge. All rights reserved.</p>
            <div class="mt-2 space-x-4">
                <a href="{{ route('terms') }}" class="hover:text-white transition-colors">Terms & Conditions</a>
                <span>•</span>
                <a href="{{ route('privacy') }}" class="hover:text-white transition-colors">Privacy Policy</a>
            </div>
        </div>
    </div>
</footer>

</body>
</html>
