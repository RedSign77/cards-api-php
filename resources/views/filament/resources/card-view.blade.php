<div class="space-y-6">
    {{-- Card Image --}}
    @if($record->image)
        <div class="flex justify-center">
            <img src="{{ Storage::disk('public')->url($record->image) }}"
                 alt="{{ $record->name }}"
                 class="rounded-lg shadow-lg max-w-md w-full object-cover">
        </div>
    @endif

    {{-- Card Details --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Game</h3>
            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $record->game->name ?? 'N/A' }}</p>
        </div>

        <div>
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Type</h3>
            <p class="mt-1">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-200">
                    {{ $record->cardType->name ?? 'N/A' }}
                </span>
            </p>
        </div>
    </div>

    {{-- Card Text --}}
    @if($record->card_text)
        <div>
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</h3>
            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $record->card_text }}</p>
        </div>
    @endif

    {{-- Card Fields --}}
    @if($record->card_data && count($record->card_data) > 0)
        <div>
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Card Fields</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($record->card_data as $field)
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ $field['fieldname'] ?? 'N/A' }}
                            </span>
                            <span class="text-sm text-gray-900 dark:text-gray-100 font-semibold">
                                {{ $field['fieldvalue'] ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Metadata --}}
    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-gray-500 dark:text-gray-400">
            <div>
                <span class="font-medium">Created:</span>
                {{ $record->created_at->format('Y-m-d H:i:s') }}
            </div>
            <div>
                <span class="font-medium">Updated:</span>
                {{ $record->updated_at->format('Y-m-d H:i:s') }}
            </div>
        </div>
    </div>
</div>
