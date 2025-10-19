<div class="space-y-6">
    {{-- Review Status Alert --}}
    <div class="rounded-lg p-4 {{ $record->is_critical ? 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800' : 'bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800' }}">
        <div class="flex items-start gap-3">
            @if($record->is_critical)
                <svg class="w-6 h-6 text-red-600 dark:text-red-400 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
            @else
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                </svg>
            @endif
            <div class="flex-1">
                <h3 class="text-sm font-semibold {{ $record->is_critical ? 'text-red-800 dark:text-red-200' : 'text-blue-800 dark:text-blue-200' }}">
                    {{ $record->is_critical ? 'Critical Review Required' : 'Under Review' }}
                </h3>
                @if($record->evaluation_flags && count($record->evaluation_flags) > 0)
                    <p class="text-sm {{ $record->is_critical ? 'text-red-700 dark:text-red-300' : 'text-blue-700 dark:text-blue-300' }} mt-1">
                        <strong>Flagged Issues:</strong>
                        @foreach($record->evaluation_flags as $flag)
                            <span class="inline-block px-2 py-0.5 rounded text-xs font-medium bg-white dark:bg-gray-800 {{ $record->is_critical ? 'text-red-700 dark:text-red-300' : 'text-blue-700 dark:text-blue-300' }} mt-1 mr-1">
                                {{ str_replace('_', ' ', ucwords($flag, '_')) }}
                            </span>
                        @endforeach
                    </p>
                @endif
            </div>
        </div>
    </div>

    {{-- Seller Information --}}
    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3 flex items-center gap-2">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
            Seller Information
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
            <div>
                <span class="text-gray-500 dark:text-gray-400">Name:</span>
                <span class="ml-2 font-medium text-gray-900 dark:text-gray-100">{{ $record->user->name }}</span>
            </div>
            <div>
                <span class="text-gray-500 dark:text-gray-400">Email:</span>
                <span class="ml-2 font-medium text-gray-900 dark:text-gray-100">{{ $record->user->email }}</span>
            </div>
            <div>
                <span class="text-gray-500 dark:text-gray-400">Approved Listings:</span>
                <span class="ml-2 font-medium text-gray-900 dark:text-gray-100">
                    {{ \App\Models\PhysicalCard::where('user_id', $record->user_id)->where('status', \App\Models\PhysicalCard::STATUS_APPROVED)->count() }}
                </span>
            </div>
            <div>
                <span class="text-gray-500 dark:text-gray-400">Member Since:</span>
                <span class="ml-2 font-medium text-gray-900 dark:text-gray-100">{{ $record->user->created_at->format('M Y') }}</span>
            </div>
        </div>
    </div>

    {{-- Card Image Section --}}
    @if($record->image)
    <div class="flex justify-center bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
        <img src="{{ asset('storage/' . $record->image) }}"
             alt="{{ $record->title }}"
             class="max-w-full h-auto rounded-lg shadow-lg"
             style="max-height: 400px;">
    </div>
    @else
    <div class="flex justify-center bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
        <div class="text-center">
            <img src="{{ asset('/images/placeholder-card.svg') }}"
                 alt="No image"
                 class="max-w-full h-auto rounded-lg opacity-50 mx-auto"
                 style="max-height: 200px;">
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">No image provided</p>
        </div>
    </div>
    @endif

    {{-- Card Information Section --}}
    <div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Card Details</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Title</h4>
                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-semibold">{{ $record->title }}</p>
            </div>

            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Set</h4>
                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $record->set ?? 'N/A' }}</p>
            </div>

            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Edition</h4>
                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $record->edition ?? 'N/A' }}</p>
            </div>

            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Language</h4>
                <p class="mt-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                        {{ $record->language }}
                    </span>
                </p>
            </div>

            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Condition</h4>
                <p class="mt-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if(in_array($record->condition, ['Mint', 'Near Mint'])) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                        @elseif(in_array($record->condition, ['Excellent', 'Good', 'Light Played'])) bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                        @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                        @endif">
                        {{ $record->condition }}
                    </span>
                </p>
            </div>

            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Quantity</h4>
                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-semibold">{{ $record->quantity }}</p>
            </div>
        </div>

        @if($record->description)
        <div class="mt-4">
            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Description</h4>
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $record->description }}</p>
            </div>
        </div>
        @endif
    </div>

    {{-- Pricing Section --}}
    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Pricing & Availability</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Price per Unit</h4>
                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-semibold">
                    @if($record->price_per_unit)
                        {{ number_format($record->price_per_unit, 2) }} {{ $record->currency }}
                    @else
                        N/A
                    @endif
                </p>
            </div>

            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Currency</h4>
                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $record->currency }}</p>
            </div>

            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Available for Trade</h4>
                <p class="mt-1">
                    @if($record->tradeable)
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                        </svg>
                    @else
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94 8.28 7.22Z" clip-rule="evenodd" />
                        </svg>
                    @endif
                </p>
            </div>
        </div>
    </div>

    {{-- Submission Date --}}
    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
        <div class="text-sm text-gray-600 dark:text-gray-400">
            <strong>Submitted:</strong> {{ $record->created_at->format('Y-m-d H:i:s') }} ({{ $record->created_at->diffForHumans() }})
        </div>
    </div>
</div>
