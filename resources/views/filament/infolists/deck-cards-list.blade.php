@php
    use App\Models\Card;

    $deckData = $getRecord()->deck_data;

    // Decode if it's a JSON string
    if (is_string($deckData)) {
        $deckData = json_decode($deckData, true);
    }
@endphp

<div class="space-y-2">
    @if (!empty($deckData) && is_array($deckData))
        <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Card Name
                        </th>
                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Quantity
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($deckData as $item)
                        @php
                            $card = isset($item['card_id']) ? Card::find($item['card_id']) : null;
                            $quantity = $item['quantity'] ?? 1;
                        @endphp

                        @if ($card)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $card->name }}
                                </td>
                                <td class="px-4 py-3 text-sm text-right text-gray-900 dark:text-gray-100">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        {{ $quantity }}
                                    </span>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
            <strong>Total Cards:</strong> {{ collect($deckData)->sum('quantity') }}
        </div>
    @else
        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
            <p>No cards in this deck yet.</p>
        </div>
    @endif
</div>
