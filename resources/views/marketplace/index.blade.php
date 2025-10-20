<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Browse Marketplace - Cards Forge</title>
    <meta name="description" content="Browse physical collectible cards for sale. Filter by condition, price, and more.">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
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

<!-- Header Section -->
<section class="py-12 bg-slate-800/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold text-white mb-4">Browse Marketplace</h1>
        <p class="text-xl text-slate-300">Discover and buy physical collectible cards from verified sellers</p>
    </div>
</section>

<!-- Main Content -->
<section class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:grid lg:grid-cols-4 lg:gap-8">

            <!-- Filters Sidebar -->
            <div class="lg:col-span-1 mb-8 lg:mb-0">
                <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl p-6 border border-slate-700/50 sticky top-24">
                    <h2 class="text-xl font-bold text-white mb-6">Filters</h2>

                    <form method="GET" action="{{ route('marketplace.index') }}" class="space-y-6">
                        <!-- Search -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-slate-300 mb-2">Search</label>
                            <input type="text"
                                   id="search"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Card name, set..."
                                   class="w-full px-4 py-2 bg-slate-900 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:border-red-500">
                        </div>

                        <!-- Condition Filter -->
                        @if($conditions->isNotEmpty())
                        <div>
                            <label for="condition" class="block text-sm font-medium text-slate-300 mb-2">Condition</label>
                            <select id="condition"
                                    name="condition"
                                    class="w-full px-4 py-2 bg-slate-900 border border-slate-600 rounded-lg text-white focus:outline-none focus:border-red-500">
                                <option value="">All Conditions</option>
                                @foreach($conditions as $condition)
                                    <option value="{{ $condition }}" {{ request('condition') == $condition ? 'selected' : '' }}>
                                        {{ ucfirst($condition) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- Language Filter -->
                        @if($languages->isNotEmpty())
                        <div>
                            <label for="language" class="block text-sm font-medium text-slate-300 mb-2">Language</label>
                            <select id="language"
                                    name="language"
                                    class="w-full px-4 py-2 bg-slate-900 border border-slate-600 rounded-lg text-white focus:outline-none focus:border-red-500">
                                <option value="">All Languages</option>
                                @foreach($languages as $language)
                                    <option value="{{ $language }}" {{ request('language') == $language ? 'selected' : '' }}>
                                        {{ $language }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- Price Range -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Price Range</label>
                            <div class="flex gap-2">
                                <input type="number"
                                       name="min_price"
                                       value="{{ request('min_price') }}"
                                       placeholder="Min"
                                       step="0.01"
                                       class="w-1/2 px-3 py-2 bg-slate-900 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:border-red-500">
                                <input type="number"
                                       name="max_price"
                                       value="{{ request('max_price') }}"
                                       placeholder="Max"
                                       step="0.01"
                                       class="w-1/2 px-3 py-2 bg-slate-900 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:border-red-500">
                            </div>
                        </div>

                        <!-- Tradeable Filter -->
                        <div>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox"
                                       name="tradeable"
                                       value="1"
                                       {{ request('tradeable') ? 'checked' : '' }}
                                       class="w-5 h-5 text-red-600 bg-slate-900 border-slate-600 rounded focus:ring-red-500">
                                <span class="ml-2 text-sm text-slate-300">Tradeable only</span>
                            </label>
                        </div>

                        <!-- Sort -->
                        <div>
                            <label for="sort_by" class="block text-sm font-medium text-slate-300 mb-2">Sort By</label>
                            <select id="sort_by"
                                    name="sort_by"
                                    class="w-full px-4 py-2 bg-slate-900 border border-slate-600 rounded-lg text-white focus:outline-none focus:border-red-500">
                                <option value="latest" {{ request('sort_by') == 'latest' ? 'selected' : '' }}>Latest First</option>
                                <option value="oldest" {{ request('sort_by') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                <option value="price_asc" {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                            </select>
                        </div>

                        <div class="pt-4 space-y-2">
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                                Apply Filters
                            </button>
                            <a href="{{ route('marketplace.index') }}" class="block w-full text-center bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg transition-colors">
                                Clear Filters
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Cards Grid -->
            <div class="lg:col-span-3">
                @if($cards->isEmpty())
                    <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl p-12 border border-slate-700/50 text-center">
                        <svg class="w-20 h-20 text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <h3 class="text-2xl font-bold text-white mb-2">No Cards Found</h3>
                        <p class="text-slate-300 mb-6">Try adjusting your filters or check back later for new listings.</p>
                        <a href="{{ route('marketplace.index') }}" class="inline-block bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg transition-colors">
                            View All Cards
                        </a>
                    </div>
                @else
                    <div class="mb-6 flex justify-between items-center">
                        <p class="text-slate-300">
                            Showing <span class="font-semibold text-white">{{ $cards->firstItem() }}</span> to
                            <span class="font-semibold text-white">{{ $cards->lastItem() }}</span> of
                            <span class="font-semibold text-white">{{ $cards->total() }}</span> results
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($cards as $card)
                        <a href="{{ route('marketplace.show', $card) }}" class="group block">
                            <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 overflow-hidden transition-all duration-200 hover:border-red-500 hover:transform hover:-translate-y-1">
                                <!-- Card Image -->
                                <div class="aspect-[3/4] bg-slate-900 relative overflow-hidden">
                                    @if($card->image)
                                        <img src="{{ Storage::url($card->image) }}"
                                             alt="{{ $card->title }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
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

                                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-700">
                                        <div>
                                            <p class="text-2xl font-bold text-white">
                                                ${{ number_format($card->price_per_unit, 2) }}
                                            </p>
                                            <p class="text-xs text-slate-400">{{ $card->quantity }} available</p>
                                        </div>

                                        <div class="text-right">
                                            <p class="text-xs text-slate-400">Seller</p>
                                            <p class="text-sm text-slate-300 font-medium">{{ $card->user->name }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $cards->links() }}
                    </div>
                @endif
            </div>
        </div>
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
