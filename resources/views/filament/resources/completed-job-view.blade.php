<div class="space-y-4">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">UUID</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 font-mono">{{ $record->uuid }}</p>
        </div>

        <div>
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Job Type</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $record->job_class ?? 'N/A' }}</p>
        </div>

        <div>
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Connection</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $record->connection }}</p>
        </div>

        <div>
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Queue</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $record->queue }}</p>
        </div>

        <div>
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Attempts</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $record->attempts }}</p>
        </div>

        <div>
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Execution Time</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $record->execution_time_human }}</p>
        </div>

        <div class="col-span-2">
            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Completed At</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $record->completed_at->format('Y-m-d H:i:s') }} ({{ $record->completed_at->diffForHumans() }})</p>
        </div>
    </div>

    <div>
        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Payload</h3>
        <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-4 overflow-auto max-h-96">
            <pre class="text-xs font-mono text-gray-800 dark:text-gray-200">{{ json_encode($record->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
        </div>
    </div>

    @if($record->job_data)
    <div>
        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Job Data</h3>
        <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-4 overflow-auto max-h-96">
            <pre class="text-xs font-mono text-gray-800 dark:text-gray-200">{{ print_r($record->job_data, true) }}</pre>
        </div>
    </div>
    @endif
</div>
