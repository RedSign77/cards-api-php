<x-filament-widgets::widget class="fi-quick-links-widget">
    <x-filament::section>
        <x-slot name="heading">
            Quick Actions
        </x-slot>

        <div class="space-y-2">
            <x-filament::button
                color="primary"
                icon="heroicon-o-puzzle-piece"
                tag="a"
                href="{{ \App\Filament\Resources\GameResource::getUrl('create') }}"
                class="w-full justify-start"
            >
                Create a Game
            </x-filament::button>

            <x-filament::button
                color="primary"
                icon="heroicon-o-rectangle-stack"
                tag="a"
                href="{{ \App\Filament\Resources\DeckResource::getUrl('create') }}"
                class="w-full justify-start"
            >
                Create a Deck
            </x-filament::button>

            <x-filament::button
                color="primary"
                icon="heroicon-o-tag"
                tag="a"
                href="{{ \App\Filament\Resources\CardTypeResource::getUrl('create') }}"
                class="w-full justify-start"
            >
                Create a Card Type
            </x-filament::button>

            <x-filament::button
                color="primary"
                icon="heroicon-o-rectangle-group"
                tag="a"
                href="{{ \App\Filament\Resources\CardResource::getUrl('create') }}"
                class="w-full justify-start"
            >
                Create a Card
            </x-filament::button>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
