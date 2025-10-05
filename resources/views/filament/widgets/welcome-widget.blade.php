<x-filament-widgets::widget>
    <x-filament::section>
        <div class="space-y-6">
            <!-- Header -->
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Üdv, {{ $userName }}! 👋
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Üdvözlünk a Cards Forge admin felületén. Itt kezelheted a kártyajáték rendszeredet.
                </p>
            </div>

            <!-- Features Grid -->
            <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">
                    Funkciók
                </h3>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($features as $feature)
                        <div class="flex items-start p-4 space-x-3 transition-all duration-200 bg-gray-50 rounded-lg dark:bg-gray-800/50 hover:shadow-md hover:bg-gray-100 dark:hover:bg-gray-800">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary-100 dark:bg-primary-900/20">
                                    <x-filament::icon
                                        :icon="$feature['icon']"
                                        class="w-5 h-5 text-primary-600 dark:text-primary-400"
                                    />
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $feature['title'] }}
                                </h4>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ $feature['description'] }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Quick Info -->
            <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between p-4 transition-all duration-200 bg-blue-50 rounded-lg dark:bg-blue-900/20 hover:shadow-md">
                    <div class="flex items-center space-x-3">
                        <x-filament::icon
                            icon="heroicon-o-information-circle"
                            class="w-5 h-5 text-blue-600 dark:text-blue-400"
                        />
                        <div>
                            <p class="text-sm font-medium text-blue-900 dark:text-blue-100">
                                REST API elérhető
                            </p>
                            <p class="text-xs text-blue-700 dark:text-blue-300">
                                Sanctum token authentication • /api/v1 endpoint
                            </p>
                        </div>
                    </div>
                    <span class="text-sm font-medium text-blue-600 dark:text-blue-400">
                        /api/v1 →
                    </span>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
