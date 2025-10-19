<div class="space-y-4">
    {{-- Action Summary --}}
    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">Action Summary</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">Action Type:</span>
                <p class="mt-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($record->action_type === 'card_created') bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                        @elseif($record->action_type === 'auto_evaluation') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                        @elseif($record->action_type === 'supervisor_approval') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                        @elseif($record->action_type === 'supervisor_rejection') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                        @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                        @endif">
                        {{ ucwords(str_replace('_', ' ', $record->action_type)) }}
                    </span>
                </p>
            </div>
            <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">Changed By:</span>
                <p class="mt-1 text-sm font-medium text-gray-900 dark:text-gray-100">
                    {{ $record->user_id === 1 ? 'System (Auto)' : ($record->user?->name ?? 'Unknown') }}
                </p>
            </div>
            <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">Status Change:</span>
                <p class="mt-1 text-sm font-medium text-gray-900 dark:text-gray-100">
                    {{ $record->old_status ? ucwords(str_replace('_', ' ', $record->old_status)) : 'N/A' }}
                    →
                    {{ ucwords(str_replace('_', ' ', $record->new_status)) }}
                </p>
            </div>
            <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">Timestamp:</span>
                <p class="mt-1 text-sm font-medium text-gray-900 dark:text-gray-100">
                    {{ $record->created_at->format('Y-m-d H:i:s') }}
                    <span class="text-xs text-gray-500 dark:text-gray-400">({{ $record->created_at->diffForHumans() }})</span>
                </p>
            </div>
        </div>
    </div>

    {{-- Card Information --}}
    @if($record->physicalCard)
    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">Card Information</h3>
        <div class="space-y-2 text-sm">
            <div>
                <span class="text-gray-500 dark:text-gray-400">Title:</span>
                <span class="ml-2 font-medium text-gray-900 dark:text-gray-100">{{ $record->physicalCard->title }}</span>
            </div>
            <div>
                <span class="text-gray-500 dark:text-gray-400">Seller:</span>
                <span class="ml-2 font-medium text-gray-900 dark:text-gray-100">{{ $record->physicalCard->user->name }}</span>
            </div>
            <div>
                <span class="text-gray-500 dark:text-gray-400">Current Status:</span>
                <span class="ml-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($record->physicalCard->status === 'pending_auto') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                        @elseif($record->physicalCard->status === 'under_review') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                        @elseif($record->physicalCard->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                        @elseif($record->physicalCard->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                        @else bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                        @endif">
                        {{ ucwords(str_replace('_', ' ', $record->physicalCard->status)) }}
                    </span>
                </span>
            </div>
        </div>
    </div>
    @endif

    {{-- Notes --}}
    @if($record->notes)
    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Notes</h3>
        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $record->notes }}</p>
    </div>
    @endif

    {{-- Metadata --}}
    @if($record->metadata && count($record->metadata) > 0)
    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">Additional Details</h3>
        <div class="space-y-2 text-sm">
            @foreach($record->metadata as $key => $value)
                <div>
                    <span class="text-gray-500 dark:text-gray-400">{{ ucwords(str_replace('_', ' ', $key)) }}:</span>
                    <span class="ml-2 font-medium text-gray-900 dark:text-gray-100">
                        @if(is_array($value))
                            {{ implode(', ', array_map(fn($v) => ucwords(str_replace('_', ' ', $v)), $value)) }}
                        @elseif(is_bool($value))
                            {{ $value ? 'Yes' : 'No' }}
                        @else
                            {{ $value }}
                        @endif
                    </span>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
