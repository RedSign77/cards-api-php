<div class="p-4 rounded-lg bg-danger-50 dark:bg-danger-900/20 border border-danger-200 dark:border-danger-800">
    <div class="flex items-start gap-3">
        <x-heroicon-o-exclamation-circle class="w-5 h-5 text-danger-500 mt-0.5 flex-shrink-0" />
        <div>
            <h3 class="text-sm font-semibold text-danger-700 dark:text-danger-400">AI Analysis Failed</h3>
            <p class="text-sm text-danger-600 dark:text-danger-300 mt-1">{{ $error }}</p>
        </div>
    </div>
</div>
