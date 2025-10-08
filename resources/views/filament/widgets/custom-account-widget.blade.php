@php
    $user = filament()->auth()->user();
    $stats = $this->getUserStats();
@endphp

<x-filament-widgets::widget class="fi-account-widget">
    <x-filament::section>
        <div class="flex items-center gap-x-3">
            <x-filament-panels::avatar.user size="lg" :user="$user" />

            <div class="flex-1">
                <h2
                    class="grid flex-1 text-base font-semibold leading-6 text-gray-950 dark:text-white"
                >
                    {{ __('filament-panels::widgets/account-widget.welcome', ['app' => config('app.name')]) }}
                </h2>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ filament()->getUserName($user) }}
                </p>
            </div>

            <div class="flex gap-2 my-auto">
                <x-filament::button
                    color="gray"
                    icon="heroicon-m-user-circle"
                    labeled-from="sm"
                    tag="a"
                    href="{{ \Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage::getUrl() }}"
                >
                    Profile
                </x-filament::button>

                <form
                    action="{{ filament()->getLogoutUrl() }}"
                    method="post"
                >
                    @csrf

                    <x-filament::button
                        color="gray"
                        icon="heroicon-m-arrow-left-on-rectangle"
                        icon-alias="panels::widgets.account.logout-button"
                        labeled-from="sm"
                        tag="button"
                        type="submit"
                    >
                        {{ __('filament-panels::widgets/account-widget.actions.logout.label') }}
                    </x-filament::button>
                </form>
            </div>
        </div>

        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <div class="text-sm text-gray-700 dark:text-gray-300">
                <span class="font-semibold">Your Content:</span>
                <span class="ml-2">
                    <span class="font-bold text-primary-600 dark:text-primary-400">{{ $stats['games'] }}</span> Games,
                    <span class="font-bold text-primary-600 dark:text-primary-400">{{ $stats['decks'] }}</span> Decks,
                    <span class="font-bold text-primary-600 dark:text-primary-400">{{ $stats['cardTypes'] }}</span> Card Types,
                    <span class="font-bold text-primary-600 dark:text-primary-400">{{ $stats['cards'] }}</span> Cards,
                    <span class="font-bold text-primary-600 dark:text-primary-400">{{ $stats['hexas'] }}</span> Hexas and
                    <span class="font-bold text-primary-600 dark:text-primary-400">{{ $stats['figures'] }}</span> Figures
                </span>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
