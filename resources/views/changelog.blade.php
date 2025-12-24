<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Changelog - {{ setting('site_name', 'Cards Forge') }}</title>
    <meta name="description" content="View all updates, features, and improvements to {{ setting('site_name', 'Cards Forge') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset(setting('favicon_32', '/favicon-32x32.png')) }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset(setting('favicon_16', '/favicon-16x16.png')) }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Instrument Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #1e293b 0%, #334155 50%, #475569 100%);
        }
        .version-badge {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.3);
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
                <a href="/" class="text-slate-300 hover:text-white transition-colors duration-200 font-medium">
                    Home
                </a>
                <a href="{{ route('changelog') }}" class="text-white font-medium">
                    Changelog
                </a>
                <a href="{{ route('marketplace.index') }}" class="text-slate-300 hover:text-white transition-colors duration-200 font-medium">
                    Marketplace
                </a>
                @auth
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

<!-- Main Content -->
<main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="text-center mb-12">
        <div class="flex items-center justify-center gap-3 mb-4">
            <span class="text-5xl">♠</span>
            <h1 class="text-5xl font-bold">Changelog</h1>
            <span class="text-5xl">♥</span>
        </div>
        <p class="text-xl text-slate-300 max-w-2xl mx-auto">
            Track all updates, new features, and improvements to {{ setting('site_name', 'Cards Forge') }}
        </p>

        <!-- Current Version Badge -->
        <div class="mt-6 inline-flex items-center gap-2 version-badge text-white px-6 py-3 rounded-full">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="font-semibold">Current Version: {{ $currentVersion }}</span>
            @if($versionDate)
            <span class="text-red-100">•</span>
            <span class="text-red-100">{{ $versionDate }}</span>
            @endif
        </div>
    </div>

    <!-- Version Cards -->
    <div class="space-y-8">
        @forelse($versions as $version)
        <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 overflow-hidden card-hover">
            <!-- Version Header -->
            <div class="bg-gradient-to-r from-slate-700/50 to-slate-800/50 px-6 py-4 border-b border-slate-700/50">
                <div class="flex items-center justify-between flex-wrap gap-3">
                    <div class="flex items-center gap-3">
                        <span class="text-3xl">♣</span>
                        <h2 class="text-2xl font-bold text-white">
                            Version {{ $version['number'] }}
                        </h2>
                        @if($version['number'] === $currentVersion)
                        <span class="bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded-full">
                            CURRENT
                        </span>
                        @endif
                    </div>
                    @if($version['date'])
                    <span class="text-slate-400 font-medium">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        {{ $version['date'] }}
                    </span>
                    @endif
                </div>
            </div>

            <!-- Version Content -->
            <div class="px-6 py-6">
                @foreach($version['sections'] as $sectionName => $items)
                @if(!empty($items))
                <div class="mb-6 last:mb-0">
                    <h3 class="text-lg font-semibold mb-3 flex items-center gap-2">
                        @if($sectionName === 'Added')
                        <span class="text-green-400">
                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </span>
                        <span class="text-green-400">{{ $sectionName }}</span>
                        @elseif($sectionName === 'Changed')
                        <span class="text-blue-400">
                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </span>
                        <span class="text-blue-400">{{ $sectionName }}</span>
                        @elseif($sectionName === 'Fixed')
                        <span class="text-amber-400">
                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </span>
                        <span class="text-amber-400">{{ $sectionName }}</span>
                        @elseif($sectionName === 'Removed')
                        <span class="text-red-400">
                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </span>
                        <span class="text-red-400">{{ $sectionName }}</span>
                        @else
                        <span class="text-slate-300">{{ $sectionName }}</span>
                        @endif
                    </h3>
                    <ul class="space-y-2 text-slate-300">
                        @foreach($items as $item)
                        <li class="flex items-start gap-2">
                            <span class="text-red-500 mt-1.5">♦</span>
                            <span class="flex-1">{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
                @endforeach
            </div>
        </div>
        @empty
        <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 p-12 text-center">
            <span class="text-6xl mb-4 block">♠</span>
            <p class="text-xl text-slate-300">No changelog entries found.</p>
        </div>
        @endforelse
    </div>

    <!-- Back to Home -->
    <div class="mt-12 text-center">
        <a href="/" class="inline-flex items-center gap-2 text-slate-300 hover:text-white transition-colors duration-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Home
        </a>
    </div>
</main>

<!-- Footer -->
<footer class="border-t border-slate-700/50 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="text-center text-slate-400">
            <p class="text-sm">
                &copy; {{ date('Y') }} {{ setting('site_name', 'Cards Forge') }}. All rights reserved.
            </p>
            <p class="text-xs mt-2 flex items-center justify-center gap-2">
                <span>♠</span>
                <span>♥</span>
                <span>♣</span>
                <span>♦</span>
            </p>
        </div>
    </div>
</footer>
</body>
</html>
