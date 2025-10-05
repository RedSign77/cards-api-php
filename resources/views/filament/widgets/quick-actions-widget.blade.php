<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Quick Actions
        </x-slot>
        <div class="space-y-3">
            @foreach ($this->getActions() as $action)
                <a href="{{ $action['url'] }}"
                   class="flex items-center p-4 space-x-4 transition-all duration-200 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 hover:shadow-md hover:scale-105 hover:border-{{ $action['color'] }}-500 dark:hover:border-{{ $action['color'] }}-500">

                    <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 rounded-full bg-{{ $action['color'] }}-100 dark:bg-{{ $action['color'] }}-900/20">
                        <x-filament::icon
                            :icon="$action['icon']"
                            class="w-6 h-6 text-{{ $action['color'] }}-600 dark:text-{{ $action['color'] }}-400"
                        />
                    </div>

                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                            {{ $action['label'] }}
                        </h3>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            {{ $action['description'] }}
                        </p>
                    </div>

                    <x-filament::icon
                        icon="heroicon-m-arrow-right"
                        class="w-5 h-5 text-gray-400"
                    />
                </a>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
