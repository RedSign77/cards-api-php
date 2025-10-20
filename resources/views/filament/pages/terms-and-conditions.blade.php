<x-filament-panels::page>
    <div class="flex flex-col items-center justify-center py-12">
        <div class="text-center space-y-4">
            <x-filament::icon
                icon="heroicon-o-arrow-top-right-on-square"
                class="h-16 w-16 text-gray-400 dark:text-gray-600 mx-auto"
            />
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                Opening Terms & Conditions
            </h2>
            <p class="text-gray-600 dark:text-gray-400">
                The Terms & Conditions page is opening in a new tab.
            </p>
            <p class="text-sm text-gray-500 dark:text-gray-500">
                If the page didn't open automatically, <a href="{{ route('terms') }}" target="_blank" class="text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 underline">click here</a>.
            </p>
            <div class="pt-4">
                <x-filament::button
                    tag="a"
                    href="{{ \Filament\Facades\Filament::getUrl() }}"
                    color="gray"
                >
                    Return to Dashboard
                </x-filament::button>
            </div>
        </div>
    </div>
</x-filament-panels::page>
