<div class="space-y-6">
    {{-- Activity Details Section --}}
    <div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Activity Details</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-2">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                    </svg>
                    Supervisor
                </h4>
                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $record->supervisor->name ?? 'N/A' }}</p>
            </div>

            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Action</h4>
                <p class="mt-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($record->action === 'approve_user')
                            bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                        @elseif($record->action === 'run_job')
                            bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                        @elseif($record->action === 'retry_failed_job')
                            bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                        @elseif(str_contains($record->action, 'delete'))
                            bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                        @else
                            bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                        @endif
                    ">
                        {{ str_replace('_', ' ', ucwords($record->action, '_')) }}
                    </span>
                </p>
            </div>

            @if($record->resource_type)
            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Resource Type</h4>
                <p class="mt-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-200">
                        {{ $record->resource_type }}
                    </span>
                </p>
            </div>
            @endif

            @if($record->resource_id)
            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Resource ID</h4>
                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $record->resource_id }}</p>
            </div>
            @endif

            <div class="md:col-span-2">
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-2">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Date & Time
                </h4>
                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $record->created_at->format('Y-m-d H:i:s') }}</p>
            </div>
        </div>
    </div>

    {{-- Description Section --}}
    @if($record->description)
    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Description</h3>
        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
            <p class="text-sm text-gray-900 dark:text-gray-100">{{ $record->description }}</p>
        </div>
    </div>
    @endif

    {{-- Metadata Section --}}
    @if($record->metadata && count($record->metadata) > 0)
    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Additional Details</h3>
        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 overflow-hidden">
            <pre class="text-xs text-gray-900 dark:text-gray-100 font-mono overflow-x-auto whitespace-pre-wrap break-words">{{ json_encode($record->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
        </div>
    </div>
    @endif

    {{-- Technical Information Section --}}
    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Technical Information</h3>
        <div class="space-y-4">
            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-2">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" />
                    </svg>
                    IP Address
                </h4>
                <div class="mt-1 flex items-center gap-2">
                    <p class="text-sm text-gray-900 dark:text-gray-100 font-mono bg-gray-50 dark:bg-gray-800 px-3 py-1.5 rounded">
                        {{ $record->ip_address ?? 'N/A' }}
                    </p>
                </div>
            </div>

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
        </div>
    </div>
</div>
