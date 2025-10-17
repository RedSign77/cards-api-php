<div class="space-y-6">
    {{-- Session Details Section --}}
    <div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Session Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-2">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                    </svg>
                    Session ID
                </h4>
                <p class="mt-1 text-xs text-gray-900 dark:text-gray-100 font-mono bg-gray-50 dark:bg-gray-800 px-3 py-1.5 rounded break-all">
                    {{ $record->id }}
                </p>
            </div>

            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-2">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    User
                </h4>
                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $record->user->name ?? 'Guest' }}</p>
            </div>

            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-2">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" />
                    </svg>
                    IP Address
                </h4>
                <div class="mt-1 flex items-center gap-2">
                    <p class="text-sm text-gray-900 dark:text-gray-100 font-mono bg-gray-50 dark:bg-gray-800 px-3 py-1.5 rounded">
                        @if($record->ip_address)
                            <a href="https://whatismyipaddress.com/ip/{{ $record->ip_address }}" target="_blank" class="text-primary-600 hover:underline">
                                {{ $record->ip_address }}
                            </a>
                        @else
                            N/A
                        @endif
                    </p>
                </div>
            </div>

            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-2">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Last Activity
                </h4>
                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                    {{ is_numeric($record->last_activity) ? date('Y-m-d H:i:s', (int) $record->last_activity) : $record->last_activity }}
                </p>
            </div>
        </div>
    </div>

    {{-- Technical Information Section --}}
    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Technical Information</h3>
        <div class="space-y-4">
            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-2 mb-2">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
                    </svg>
                    User Agent
                </h4>
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                    <p class="text-xs text-gray-900 dark:text-gray-100 font-mono break-all">
                        {{ $record->user_agent ?? 'N/A' }}
                    </p>
                </div>
            </div>

            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-2 mb-2">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5" />
                    </svg>
                    Payload
                </h4>
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3 max-h-96 overflow-auto">
                    <pre class="text-xs text-gray-900 dark:text-gray-100 font-mono whitespace-pre-wrap break-all">{{ $record->payload ?? 'N/A' }}</pre>
                </div>
            </div>
        </div>
    </div>
</div>
