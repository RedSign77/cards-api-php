<x-filament-widgets::widget>
    <x-filament::section>
        <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-5">
            <!-- Bal oldal: 3/5 -->
            <div class="space-y-6 lg:col-span-3">
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

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
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

            <!-- Jobb oldal: 2/5 -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Hero Icon -->
                <div class="flex items-center justify-center p-8 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl shadow-lg">
                    <x-filament::icon
                        icon="heroicon-o-sparkles"
                        class="w-32 h-32 text-white opacity-90"
                    />
                </div>

                <!-- Stats Cards -->
                <div class="space-y-3">
                    <div class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-lg border border-green-200 dark:border-green-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium text-green-600 dark:text-green-400">
                                    Rendszer Verzió
                                </p>
                                <p class="mt-1 text-lg font-bold text-green-900 dark:text-green-100">
                                    Laravel 12 + Filament 3
                                </p>
                            </div>
                            <x-filament::icon
                                icon="heroicon-o-check-circle"
                                class="w-8 h-8 text-green-600 dark:text-green-400"
                            />
                        </div>
                    </div>

                    <div class="p-4 bg-gradient-to-r from-purple-50 to-violet-50 dark:from-purple-900/20 dark:to-violet-900/20 rounded-lg border border-purple-200 dark:border-purple-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium text-purple-600 dark:text-purple-400">
                                    Auth Method
                                </p>
                                <p class="mt-1 text-lg font-bold text-purple-900 dark:text-purple-100">
                                    Sanctum Tokens
                                </p>
                            </div>
                            <x-filament::icon
                                icon="heroicon-o-shield-check"
                                class="w-8 h-8 text-purple-600 dark:text-purple-400"
                            />
                        </div>
                    </div>

                    <div class="p-4 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-lg border border-amber-200 dark:border-amber-800">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium text-amber-600 dark:text-amber-400">
                                    Database
                                </p>
                                <p class="mt-1 text-lg font-bold text-amber-900 dark:text-amber-100">
                                    MySQL / SQLite
                                </p>
                            </div>
                            <x-filament::icon
                                icon="heroicon-o-circle-stack"
                                class="w-8 h-8 text-amber-600 dark:text-amber-400"
                            />
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                    <h4 class="mb-3 text-sm font-semibold text-gray-900 dark:text-white">
                        Gyors Linkek
                    </h4>
                    <div class="space-y-2">
                        <a href="{{ \App\Filament\Resources\CardResource::getUrl('index') }}"
                           class="flex items-center justify-between p-2 text-sm transition-colors rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                            <span class="text-gray-700 dark:text-gray-300">Összes kártya</span>
                            <x-filament::icon
                                icon="heroicon-m-arrow-right"
                                class="w-4 h-4 text-gray-400"
                            />
                        </a>
                        <a href="{{ \App\Filament\Resources\GameResource::getUrl('index') }}"
                           class="flex items-center justify-between p-2 text-sm transition-colors rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                            <span class="text-gray-700 dark:text-gray-300">Játékok</span>
                            <x-filament::icon
                                icon="heroicon-m-arrow-right"
                                class="w-4 h-4 text-gray-400"
                            />
                        </a>
                        <a href="{{ \App\Filament\Resources\UserResource::getUrl('index') }}"
                           class="flex items-center justify-between p-2 text-sm transition-colors rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                            <span class="text-gray-700 dark:text-gray-300">Felhasználók</span>
                            <x-filament::icon
                                icon="heroicon-m-arrow-right"
                                class="w-4 h-4 text-gray-400"
                            />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
