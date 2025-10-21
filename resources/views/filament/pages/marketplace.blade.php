<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Filters Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            {{-- Filters Sidebar --}}
            <div class="lg:col-span-1">
                <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                    <div class="fi-section-content p-4 space-y-3">
                        {{-- Search --}}
                        <div>
                            <input
                                type="text"
                                wire:model.live.debounce.500ms="search"
                                placeholder="Search cards..."
                                class="fi-input block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                            />
                        </div>

                        {{-- Condition Filter --}}
                        @if($this->getConditions()->isNotEmpty())
                        <div>
                            <select
                                wire:model.live="condition"
                                class="fi-input block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                            >
                                <option value="">All Conditions</option>
                                @foreach($this->getConditions() as $conditionOption)
                                    <option value="{{ $conditionOption }}">{{ ucfirst($conditionOption) }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        {{-- Language Filter --}}
                        @if($this->getLanguages()->isNotEmpty())
                        <div>
                            <select
                                wire:model.live="language"
                                class="fi-input block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                            >
                                <option value="">All Languages</option>
                                @foreach($this->getLanguages() as $languageOption)
                                    <option value="{{ $languageOption }}">{{ $languageOption }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        {{-- Price Range --}}
                        <div>
                            <div class="flex gap-2">
                                <input
                                    type="number"
                                    wire:model.live.debounce.500ms="minPrice"
                                    placeholder="Min $"
                                    step="0.01"
                                    class="fi-input block w-1/2 rounded-lg border-gray-300 shadow-sm text-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                                />
                                <input
                                    type="number"
                                    wire:model.live.debounce.500ms="maxPrice"
                                    placeholder="Max $"
                                    step="0.01"
                                    class="fi-input block w-1/2 rounded-lg border-gray-300 shadow-sm text-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                                />
                            </div>
                        </div>

                        {{-- Tradeable Filter --}}
                        <div>
                            <label class="flex items-center cursor-pointer">
                                <input
                                    type="checkbox"
                                    wire:model.live="tradeableOnly"
                                    class="fi-checkbox-input rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500/50 dark:border-gray-700 dark:bg-gray-900"
                                />
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Tradeable only</span>
                            </label>
                        </div>

                        {{-- Sort --}}
                        <div>
                            <select
                                wire:model.live="sortBy"
                                class="fi-input block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                            >
                                <option value="latest">Latest First</option>
                                <option value="oldest">Oldest First</option>
                                <option value="price_asc">Price: Low to High</option>
                                <option value="price_desc">Price: High to Low</option>
                            </select>
                        </div>

                        <div class="pt-2">
                            <button
                                type="button"
                                wire:click="clearFilters"
                                class="fi-btn w-full bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-gray-300 rounded-lg px-3 py-1.5 text-sm transition-colors"
                            >
                                Clear Filters
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cards Grid --}}
            <div class="lg:col-span-3">
                @php
                    $cards = $this->getCards();
                @endphp

                @if($cards->isEmpty())
                    <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-12 text-center">
                        <div class="flex flex-col items-center">
                            <x-heroicon-o-shopping-bag class="h-20 w-20 text-gray-400 dark:text-gray-600 mb-4" />
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">No Cards Found</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6">Try adjusting your filters or check back later for new listings.</p>
                            <button
                                type="button"
                                wire:click="clearFilters"
                                class="fi-btn bg-primary-600 hover:bg-primary-700 text-white rounded-lg px-6 py-3 transition-colors"
                            >
                                View All Cards
                            </button>
                        </div>
                    </div>
                @else
                    <div class="mb-6">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Showing <span class="font-semibold text-gray-900 dark:text-white">{{ $cards->firstItem() }}</span> to
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $cards->lastItem() }}</span> of
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $cards->total() }}</span> results
                        </p>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                        @foreach($cards as $card)
                        <div class="group">
                            <div class="fi-section rounded-lg bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 overflow-hidden transition-all duration-200 hover:ring-primary-500">
                                {{-- Card Image - Absolute fixed height and width with crop --}}
                                <div class="relative overflow-hidden" style="width: 100%; height: 400px;">
                                    @if($card->image)
                                        <img src="{{ Storage::url($card->image) }}"
                                             alt="{{ $card->title }}"
                                             style="width: 100%; height: 400px; object-fit: cover; object-position: center;"
                                             class="bg-gray-100 dark:bg-gray-800">
                                    @else
                                        <div style="width: 100%; height: 400px;" class="flex items-center justify-center bg-gray-100 dark:bg-gray-800">
                                            <x-heroicon-o-photo class="h-10 w-10 text-gray-400 dark:text-gray-600" />
                                        </div>
                                    @endif

                                    {{-- View Button --}}
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-all duration-200 flex items-center justify-center">
                                        <button
                                            wire:click="viewCard({{ $card->id }})"
                                            class="opacity-0 group-hover:opacity-100 transition-opacity duration-200 bg-primary-600 hover:bg-primary-700 text-white rounded-full p-3 shadow-lg transform hover:scale-110"
                                            type="button"
                                        >
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                    </div>

                                    {{-- Condition Badge --}}
                                    <div class="absolute top-1.5 right-1.5">
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-medium bg-gray-900/90 text-white backdrop-blur-sm border border-gray-600">
                                            {{ ucfirst($card->condition) }}
                                        </span>
                                    </div>

                                    {{-- Tradeable Badge --}}
                                    @if($card->tradeable)
                                    <div class="absolute top-1.5 left-1.5">
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-medium bg-success-600/90 text-white backdrop-blur-sm">
                                            Trade
                                        </span>
                                    </div>
                                    @endif
                                </div>

                                {{-- Card Info --}}
                                <div class="p-2">
                                    <h3 class="text-xs font-semibold text-gray-900 dark:text-white mb-0.5 line-clamp-1 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                        {{ $card->title }}
                                    </h3>

                                    @if($card->set)
                                    <p class="text-[10px] text-gray-500 dark:text-gray-400 mb-1.5 line-clamp-1">{{ $card->set }}</p>
                                    @endif

                                    <div class="space-y-1 mt-1.5 pt-1.5 border-t border-gray-200 dark:border-gray-700">
                                        <div class="flex items-baseline justify-between">
                                            <p class="text-base font-bold text-gray-900 dark:text-white">
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
                                            <p class="text-[10px] text-gray-500 dark:text-gray-400">{{ $card->quantity }}</p>
                                        </div>

                                        {{-- Location and Shipping Info --}}
                                        <div class="text-[10px] text-gray-600 dark:text-gray-400 space-y-0.5">
                                            @if($card->user->seller_location)
                                                <div class="flex items-center gap-1">
                                                    <svg class="flex-shrink-0" style="width: 8px; height: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    </svg>
                                                    <span class="line-clamp-1">{{ $card->user->seller_location }}</span>
                                                </div>
                                            @endif
                                            @if($card->user->shipping_price)
                                                <div class="flex items-center gap-1">
                                                    <svg class="flex-shrink-0" style="width: 8px; height: 8px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                                                    </svg>
                                                    <span>${{ number_format($card->user->shipping_price, 2) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-8">
                        {{ $cards->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Card Detail Modal --}}
    <x-filament::modal
        id="card-detail-modal"
        wire:model="showModal"
        width="5xl"
    >
        @if($selectedCardId)
            @php
                $selectedCard = $this->getSelectedCard();
                $similarCards = $this->getSimilarCards();
            @endphp

            @if($selectedCard)
            <x-slot name="heading">
                {{ $selectedCard->title }}
            </x-slot>

            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Card Image --}}
                    <div>
                        <div class="bg-gray-100 dark:bg-gray-800 rounded-lg overflow-hidden">
                            <div class="aspect-[3/4] relative">
                                @if($selectedCard->image)
                                    <img src="{{ Storage::url($selectedCard->image) }}"
                                         alt="{{ $selectedCard->title }}"
                                         class="w-full h-full object-contain">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <x-heroicon-o-photo class="h-32 w-32 text-gray-400 dark:text-gray-600" />
                                    </div>
                                @endif

                                {{-- Condition Badge --}}
                                <div class="absolute top-4 right-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-gray-900/90 text-white backdrop-blur-sm border border-gray-600">
                                        {{ ucfirst($selectedCard->condition) }}
                                    </span>
                                </div>

                                {{-- Tradeable Badge --}}
                                @if($selectedCard->tradeable)
                                <div class="absolute top-4 left-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-success-600/90 text-white backdrop-blur-sm">
                                        Open to Trades
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Card Details --}}
                    <div class="space-y-4">
                        {{-- Price and Quantity --}}
                        <div class="fi-section rounded-lg bg-gray-50 dark:bg-gray-800 p-4">
                            <div class="flex items-end justify-between mb-4">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Price per card</p>
                                    <p class="text-4xl font-bold text-gray-900 dark:text-white">
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
                                            $symbol = $currencySymbols[$selectedCard->currency] ?? $selectedCard->currency;
                                        @endphp
                                        {{ number_format($selectedCard->price_per_unit, 2) }} {{ $symbol }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $selectedCard->currency }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Available</p>
                                    <p class="text-3xl font-bold text-success-600">{{ $selectedCard->quantity }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Card Information --}}
                        <div class="fi-section rounded-lg bg-white dark:bg-gray-900 ring-1 ring-gray-950/5 dark:ring-white/10 p-4">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-3">Card Details</h3>

                            <div class="space-y-2 text-sm">
                                @if($selectedCard->set)
                                    <div class="flex items-start">
                                        <span class="text-gray-500 dark:text-gray-400 w-24 flex-shrink-0">Set:</span>
                                        <span class="text-gray-900 dark:text-white font-medium">{{ $selectedCard->set }}</span>
                                    </div>
                                @endif

                                @if($selectedCard->edition)
                                    <div class="flex items-start">
                                        <span class="text-gray-500 dark:text-gray-400 w-24 flex-shrink-0">Edition:</span>
                                        <span class="text-gray-900 dark:text-white font-medium">{{ $selectedCard->edition }}</span>
                                    </div>
                                @endif

                                @if($selectedCard->language)
                                    <div class="flex items-start">
                                        <span class="text-gray-500 dark:text-gray-400 w-24 flex-shrink-0">Language:</span>
                                        <span class="text-gray-900 dark:text-white font-medium">{{ $selectedCard->language }}</span>
                                    </div>
                                @endif

                                <div class="flex items-start">
                                    <span class="text-gray-500 dark:text-gray-400 w-24 flex-shrink-0">Condition:</span>
                                    <span class="text-gray-900 dark:text-white font-medium">{{ $selectedCard->condition }}</span>
                                </div>

                                <div class="flex items-start">
                                    <span class="text-gray-500 dark:text-gray-400 w-24 flex-shrink-0">Tradeable:</span>
                                    <span class="text-gray-900 dark:text-white font-medium">{{ $selectedCard->tradeable ? 'Yes' : 'No' }}</span>
                                </div>

                                <div class="flex items-start">
                                    <span class="text-gray-500 dark:text-gray-400 w-24 flex-shrink-0">Listed:</span>
                                    <span class="text-gray-900 dark:text-white font-medium">{{ $selectedCard->approved_at?->format('M d, Y') ?? $selectedCard->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Description --}}
                        @if($selectedCard->description)
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-2">Description</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">{{ $selectedCard->description }}</p>
                        </div>
                        @endif

                        {{-- Seller Information --}}
                        <div class="fi-section rounded-lg bg-gray-50 dark:bg-gray-800 p-4">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-3">Seller Information</h3>

                            <div class="flex items-center mb-4">
                                @if($selectedCard->user->avatar_url)
                                    <img src="{{ Storage::url($selectedCard->user->avatar_url) }}"
                                         alt="{{ $selectedCard->user->name }}"
                                         class="w-12 h-12 rounded-full mr-3 border-2 border-gray-300 dark:border-gray-600">
                                @else
                                    <div class="w-12 h-12 rounded-full mr-3 bg-gray-300 dark:bg-gray-700 flex items-center justify-center border-2 border-gray-300 dark:border-gray-600">
                                        <span class="text-lg font-bold text-gray-600 dark:text-gray-300">{{ substr($selectedCard->user->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $selectedCard->user->name }}</p>
                                    @if($selectedCard->user->seller_location)
                                        <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            {{ $selectedCard->user->seller_location }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if($selectedCard->user->bio)
                            <div class="mb-3 pb-3 border-b border-gray-200 dark:border-gray-700">
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $selectedCard->user->bio }}</p>
                            </div>
                            @endif

                            {{-- Contact Information --}}
                            @if($selectedCard->user->email || $selectedCard->user->phone || $selectedCard->user->website)
                            <div class="mb-3 pb-3 border-b border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Contact</p>
                                <div class="space-y-1 text-sm">
                                    @if($selectedCard->user->email)
                                    <div class="flex items-center text-gray-600 dark:text-gray-400">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        <a href="mailto:{{ $selectedCard->user->email }}" class="hover:text-primary-600 dark:hover:text-primary-400">
                                            {{ $selectedCard->user->email }}
                                        </a>
                                    </div>
                                    @endif

                                    @if($selectedCard->user->phone)
                                    <div class="flex items-center text-gray-600 dark:text-gray-400">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        <a href="tel:{{ $selectedCard->user->phone }}" class="hover:text-primary-600 dark:hover:text-primary-400">
                                            {{ $selectedCard->user->phone }}
                                        </a>
                                    </div>
                                    @endif

                                    @if($selectedCard->user->website)
                                    <div class="flex items-center text-gray-600 dark:text-gray-400">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                        </svg>
                                        <a href="{{ $selectedCard->user->website }}" target="_blank" class="hover:text-primary-600 dark:hover:text-primary-400">
                                            {{ str_replace(['https://', 'http://'], '', $selectedCard->user->website) }}
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif

                            {{-- Shipping Information --}}
                            @if($selectedCard->user->shipping_options || $selectedCard->user->shipping_price || $selectedCard->user->delivery_time)
                            <div>
                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Shipping Information</p>

                                @if($selectedCard->user->shipping_options)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ $selectedCard->user->shipping_options }}</p>
                                @endif

                                <div class="space-y-1 text-sm">
                                    @if($selectedCard->user->shipping_price)
                                        <div class="flex items-center text-gray-700 dark:text-gray-300">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                                            </svg>
                                            <span>
                                                @php
                                                    $shippingCurrencySymbols = [
                                                        'USD' => '$',
                                                        'EUR' => '€',
                                                        'GBP' => '£',
                                                        'JPY' => '¥',
                                                        'CAD' => 'CA$',
                                                        'AUD' => 'A$',
                                                        'HUF' => 'Ft',
                                                    ];
                                                    $shippingSymbol = $shippingCurrencySymbols[$selectedCard->user->shipping_currency ?? 'USD'] ?? ($selectedCard->user->shipping_currency ?? 'USD');
                                                @endphp
                                                Shipping: {{ number_format($selectedCard->user->shipping_price, 2) }} {{ $shippingSymbol }}
                                            </span>
                                        </div>
                                    @endif

                                    @if($selectedCard->user->delivery_time)
                                        <div class="flex items-center text-gray-700 dark:text-gray-300">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span>Delivery: {{ $selectedCard->user->delivery_time }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Similar Cards --}}
                @if($similarCards->isNotEmpty())
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Similar Cards</h3>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($similarCards as $similarCard)
                        <div class="group">
                            <div class="fi-section rounded-lg bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 overflow-hidden transition-all duration-200 hover:ring-primary-500">
                                <div class="aspect-[3/4] bg-gray-100 dark:bg-gray-800 relative overflow-hidden">
                                    @if($similarCard->image)
                                        <img src="{{ Storage::url($similarCard->image) }}"
                                             alt="{{ $similarCard->title }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <x-heroicon-o-photo class="h-12 w-12 text-gray-400 dark:text-gray-600" />
                                        </div>
                                    @endif

                                    {{-- View Button --}}
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-all duration-200 flex items-center justify-center">
                                        <button
                                            wire:click="viewCard({{ $similarCard->id }})"
                                            class="opacity-0 group-hover:opacity-100 transition-opacity duration-200 bg-primary-600 hover:bg-primary-700 text-white rounded-full p-2 shadow-lg"
                                            type="button"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="absolute top-2 right-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-900/80 text-white backdrop-blur-sm border border-gray-600">
                                            {{ ucfirst($similarCard->condition) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="p-3">
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1 line-clamp-1">
                                        {{ $similarCard->title }}
                                    </h4>

                                    @if($similarCard->set)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">{{ $similarCard->set }}</p>
                                    @endif

                                    <p class="text-lg font-bold text-gray-900 dark:text-white">
                                        ${{ number_format($similarCard->price_per_unit, 2) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <x-slot name="footerActions">
                <x-filament::button wire:click="closeCardModal" color="gray">
                    Close
                </x-filament::button>
            </x-slot>
            @endif
        @endif
    </x-filament::modal>
</x-filament-panels::page>
