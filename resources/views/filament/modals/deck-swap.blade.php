<div class="space-y-4 p-2">

    <p class="text-sm text-gray-600 dark:text-gray-400">
        AI-suggested card swaps based on your deck composition. Click <strong>Apply</strong> on any row to execute the swap immediately.
    </p>

    @foreach($suggestions as $index => $swap)
    <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4 space-y-2"
         wire:key="swap-{{ $index }}">
        <div class="flex items-center gap-3 flex-wrap">
            <div class="flex-1 min-w-0">
                <div class="text-xs text-gray-500 mb-0.5">Remove</div>
                <div class="text-sm font-medium text-danger-600">{{ $swap['remove_name'] }}</div>
            </div>
            <div class="text-gray-400 font-bold text-lg">→</div>
            <div class="flex-1 min-w-0">
                <div class="text-xs text-gray-500 mb-0.5">Add</div>
                <div class="text-sm font-medium text-success-600">{{ $swap['replace_with_name'] }}</div>
            </div>
            <div>
                <button
                    type="button"
                    wire:click="applyCardSwap({{ $swap['remove_id'] }}, {{ $swap['replace_with_id'] }})"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-1.5 rounded-md bg-primary-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-primary-500 focus:outline-none disabled:opacity-50"
                >
                    <x-heroicon-o-check class="w-3.5 h-3.5" wire:loading.remove wire:target="applyCardSwap({{ $swap['remove_id'] }}, {{ $swap['replace_with_id'] }})" />
                    <x-heroicon-o-arrow-path class="w-3.5 h-3.5 animate-spin" wire:loading wire:target="applyCardSwap({{ $swap['remove_id'] }}, {{ $swap['replace_with_id'] }})" />
                    Apply
                </button>
            </div>
        </div>
        <p class="text-xs text-gray-500 dark:text-gray-400 border-t border-gray-100 dark:border-gray-800 pt-2">
            {{ $swap['reason'] }}
        </p>
    </div>
    @endforeach

</div>
