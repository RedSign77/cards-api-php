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
                        <div
                            wire:click="viewCard({{ $card->id }})"
                            class="group cursor-pointer"
                        >
                            <div class="fi-section rounded-lg bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 overflow-hidden transition-all duration-200 hover:ring-primary-500 hover:transform hover:-translate-y-1">
                                {{-- Card Image - Absolute fixed height and width with crop --}}
                                <div class="relative overflow-hidden" style="width: 100%; height: 200px;">
                                    @if($card->image)
                                        <img src="{{ Storage::url($card->image) }}"
                                             alt="{{ $card->title }}"
                                             style="width: 100%; height: 200px; object-fit: cover; object-position: center;"
                                             class="group-hover:scale-105 transition-transform duration-200 bg-gray-100 dark:bg-gray-800">
                                    @else
                                        <div style="width: 100%; height: 200px;" class="flex items-center justify-center bg-gray-100 dark:bg-gray-800">
                                            <x-heroicon-o-photo class="h-10 w-10 text-gray-400 dark:text-gray-600" />
                                        </div>
                                    @endif

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
                                                ${{ number_format($card->price_per_unit, 2) }}
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
    @if($this->selectedCardId)
        @php
            $selectedCard = $this->getSelectedCard();
            $similarCards = $this->getSimilarCards();
        @endphp

        @if($selectedCard)
        <x-filament::modal
            id="card-detail-modal"
            wire:model="selectedCardId"
            width="5xl"
        >
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
                        {{-- Metadata --}}
                        <div class="flex flex-wrap gap-4 text-sm text-gray-600 dark:text-gray-400">
                            @if($selectedCard->set)
                                <div class="flex items-center">
                                    <x-heroicon-o-cube class="h-5 w-5 mr-2" />
                                    <span>{{ $selectedCard->set }}</span>
                                </div>
                            @endif

                            @if($selectedCard->language)
                                <div class="flex items-center">
                                    <x-heroicon-o-language class="h-5 w-5 mr-2" />
                                    <span>{{ $selectedCard->language }}</span>
                                </div>
                            @endif

                            @if($selectedCard->edition)
                                <div class="flex items-center">
                                    <x-heroicon-o-tag class="h-5 w-5 mr-2" />
                                    <span>{{ $selectedCard->edition }}</span>
                                </div>
                            @endif
                        </div>

                        {{-- Price and Quantity --}}
                        <div class="fi-section rounded-lg bg-gray-50 dark:bg-gray-800 p-4">
                            <div class="flex items-end justify-between mb-4">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Price per card</p>
                                    <p class="text-4xl font-bold text-gray-900 dark:text-white">
                                        ${{ number_format($selectedCard->price_per_unit, 2) }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Available</p>
                                    <p class="text-3xl font-bold text-success-600">{{ $selectedCard->quantity }}</p>
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

                            <div class="flex items-center">
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
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedCard->user->seller_location }}</p>
                                    @endif
                                </div>
                            </div>

                            @if($selectedCard->user->shipping_options)
                            <div class="border-t border-gray-200 dark:border-gray-700 mt-3 pt-3">
                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Shipping Information</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $selectedCard->user->shipping_options }}</p>

                                @if($selectedCard->user->shipping_price)
                                    <p class="text-sm text-gray-700 dark:text-gray-300 mt-2">
                                        Shipping: ${{ number_format($selectedCard->user->shipping_price, 2) }}
                                        @if($selectedCard->user->delivery_time)
                                            <span class="text-gray-500 dark:text-gray-400"> • {{ $selectedCard->user->delivery_time }}</span>
                                        @endif
                                    </p>
                                @endif
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
                        <div
                            wire:click="viewCard({{ $similarCard->id }})"
                            class="group cursor-pointer"
                        >
                            <div class="fi-section rounded-lg bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 overflow-hidden transition-all duration-200 hover:ring-primary-500">
                                <div class="aspect-[3/4] bg-gray-100 dark:bg-gray-800 relative overflow-hidden">
                                    @if($similarCard->image)
                                        <img src="{{ Storage::url($similarCard->image) }}"
                                             alt="{{ $similarCard->title }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <x-heroicon-o-photo class="h-12 w-12 text-gray-400 dark:text-gray-600" />
                                        </div>
                                    @endif

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
        </x-filament::modal>
        @endif
    @endif
</x-filament-panels::page>
