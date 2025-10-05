<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            System Info
        </x-slot>

        <div class="space-y-6">
            <!-- Hero Icon -->
            <div class="flex items-center justify-center p-8 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl shadow-lg">
                <x-filament::icon
                    icon="heroicon-o-sparkles"
                    class="w-24 h-24 text-white opacity-90"
                />
            </div>

            <!-- Stats Cards -->
            <div class="space-y-3">
                @foreach ($stats as $stat)
                    <div class="p-4 bg-gradient-to-r from-{{ $stat['color'] }}-50 to-{{ $stat['color'] }}-50 dark:from-{{ $stat['color'] }}-900/20 dark:to-{{ $stat['color'] }}-900/20 rounded-lg border border-{{ $stat['color'] }}-200 dark:border-{{ $stat['color'] }}-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium text-{{ $stat['color'] }}-600 dark:text-{{ $stat['color'] }}-400">
                                    {{ $stat['label'] }}
                                </p>
                                <p class="mt-1 text-sm font-bold text-{{ $stat['color'] }}-900 dark:text-{{ $stat['color'] }}-100">
                                    {{ $stat['value'] }}
                                </p>
                            </div>
                            <x-filament::icon
                                :icon="$stat['icon']"
                                class="w-8 h-8 text-{{ $stat['color'] }}-600 dark:text-{{ $stat['color'] }}-400"
                            />
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Quick Links -->
            <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                <h4 class="mb-3 text-sm font-semibold text-gray-900 dark:text-white">
                    Gyors Linkek
                </h4>
                <div class="space-y-2">
                    @foreach ($quickLinks as $link)
                        <a href="{{ $link['url'] }}"
                           class="flex items-center justify-between p-2 text-sm transition-colors rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                            <span class="text-gray-700 dark:text-gray-300">{{ $link['label'] }}</span>
                            <x-filament::icon
                                icon="heroicon-m-arrow-right"
                                class="w-4 h-4 text-gray-400"
                            />
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
