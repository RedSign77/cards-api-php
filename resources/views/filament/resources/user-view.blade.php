<div class="space-y-6">
    {{-- Profile Picture Section --}}
    @if($record->avatar_url)
    <div class="flex justify-center">
        <img src="{{ asset('storage/' . $record->avatar_url) }}"
             alt="{{ $record->name }}"
             class="w-32 h-32 rounded-full object-cover shadow-lg ring-4 ring-gray-100 dark:ring-gray-800">
    </div>
    @endif

    {{-- Profile Information Section --}}
    <div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Profile Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-2">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    Name
                </h4>
                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-semibold">{{ $record->name }}</p>
            </div>

            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-2">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                    </svg>
                    Email
                </h4>
                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $record->email }}</p>
            </div>

            @if($record->location)
            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-2">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                    </svg>
                    Location
                </h4>
                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $record->location }}</p>
            </div>
            @endif

            @if($record->phone)
            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-2">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                    </svg>
                    Phone
                </h4>
                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $record->phone }}</p>
            </div>
            @endif

            @if($record->website)
            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-2">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" />
                    </svg>
                    Website
                </h4>
                <p class="mt-1 text-sm">
                    <a href="{{ $record->website }}" target="_blank" class="text-primary-600 hover:underline dark:text-primary-400">
                        {{ $record->website }}
                    </a>
                </p>
            </div>
            @endif

            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-2">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                    </svg>
                    Role
                </h4>
                <p class="mt-1">
                    @if($record->supervisor)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 0 0-4.5 4.5V9H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-.5V5.5A4.5 4.5 0 0 0 10 1Zm3 8V5.5a3 3 0 1 0-6 0V9h6Z" clip-rule="evenodd" />
                            </svg>
                            Supervisor
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                            <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3.465 14.493a1.23 1.23 0 0 0 .41 1.412A9.957 9.957 0 0 0 10 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 0 0-13.074.003Z" />
                            </svg>
                            User
                        </span>
                    @endif
                </p>
            </div>
        </div>

        @if($record->bio)
        <div class="mt-4">
            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-2 mb-2">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
                Bio
            </h4>
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $record->bio }}</p>
            </div>
        </div>
        @endif
    </div>

    {{-- Account Status Section --}}
    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Account Status</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-2">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Email Verified
                </h4>
                <p class="mt-1">
                    @if($record->email_verified_at)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            Verified
                        </span>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $record->email_verified_at->format('Y-m-d H:i:s') }}</p>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                            Not Verified
                        </span>
                    @endif
                </p>
            </div>

            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-2">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                    </svg>
                    Approved
                </h4>
                <p class="mt-1">
                    @if($record->approved_at)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            Approved
                        </span>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $record->approved_at->format('Y-m-d H:i:s') }}</p>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                            Pending
                        </span>
                    @endif
                </p>
            </div>

            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-2">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Member Since
                </h4>
                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $record->created_at->format('Y-m-d H:i:s') }}</p>
            </div>
        </div>
    </div>

    {{-- Marketplace Settings (if supervisor) --}}
    @if($record->supervisor && ($record->seller_location || $record->shipping_options || $record->shipping_price || $record->delivery_time))
    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Marketplace Settings</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @if($record->seller_location)
            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Seller Location</h4>
                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $record->seller_location }}</p>
            </div>
            @endif

            @if($record->delivery_time)
            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Delivery Time</h4>
                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $record->delivery_time }}</p>
            </div>
            @endif

            @if($record->shipping_price)
            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Shipping Price</h4>
                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-semibold">
                    {{ number_format($record->shipping_price, 2) }} {{ $record->shipping_currency ?? 'USD' }}
                </p>
            </div>
            @endif

            @if($record->shipping_options)
            <div class="md:col-span-2">
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Shipping Options</h4>
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                    <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $record->shipping_options }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- Metadata Section --}}
    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Metadata</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</h4>
                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $record->updated_at->format('Y-m-d H:i:s') }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $record->updated_at->diffForHumans() }}</p>
            </div>

            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">User ID</h4>
                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">#{{ $record->id }}</p>
            </div>
        </div>
    </div>
</div>
