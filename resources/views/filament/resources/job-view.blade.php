<div class="space-y-6">
    {{-- Job Details Section --}}
    <div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Job Details</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Job ID</h4>
                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $record->id }}</p>
            </div>

            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Queue</h4>
                <p class="mt-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                        {{ $record->queue }}
                    </span>
                </p>
            </div>

            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Attempts</h4>
                <p class="mt-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($record->attempts === 0)
                            bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                        @elseif($record->attempts < 3)
                            bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                        @else
                            bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                        @endif
                    ">
                        {{ $record->attempts }}
                    </span>
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Available At</h4>
                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                    {{ $record->available_at ? date('Y-m-d H:i:s', $record->available_at) : 'N/A' }}
                </p>
            </div>

            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Reserved At</h4>
                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                    {{ $record->reserved_at ? date('Y-m-d H:i:s', $record->reserved_at) : 'Not Reserved' }}
                </p>
            </div>

            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Created At</h4>
                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                    {{ $record->created_at ? date('Y-m-d H:i:s', $record->created_at) : 'N/A' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Payload Section --}}
    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Payload</h3>
        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 overflow-hidden">
            <pre class="text-xs text-gray-900 dark:text-gray-100 font-mono overflow-x-auto whitespace-pre-wrap break-words">{{ is_array($record->payload) ? json_encode($record->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : (is_string($record->payload) ? (json_decode($record->payload) ? json_encode(json_decode($record->payload), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : $record->payload) : 'N/A') }}</pre>
        </div>
        <button
            onclick="navigator.clipboard.writeText(this.previousElementSibling.querySelector('pre').textContent)"
            class="mt-2 text-xs text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 flex items-center gap-1"
        >
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" />
            </svg>
            Copy to clipboard
        </button>
    </div>
</div>
