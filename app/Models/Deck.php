<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deck extends Model
{
    protected $table = 'decks';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $casts = [
        'deck_data' => 'array',
    ];

    protected $fillable = [
        'creator_id',
        'game_id',
        'deck_name',
        'deck_description',
        'deck_data',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Add cards to the deck, avoiding duplicates and incrementing quantity
     *
     * @param array $cardIds Array of card IDs to add
     * @return void
     */
    public function addCards(array $cardIds): void
    {
        $deckData = $this->deck_data ?? [];

        foreach ($cardIds as $cardId) {
            $found = false;

            // Check if card already exists in deck
            foreach ($deckData as &$item) {
                if (isset($item['card_id']) && $item['card_id'] == $cardId) {
                    // Card exists, increment quantity
                    $item['quantity'] = ($item['quantity'] ?? 1) + 1;
                    $found = true;
                    break;
                }
            }

            // If card not found, add it with quantity 1
            if (!$found) {
                $deckData[] = [
                    'card_id' => $cardId,
                    'quantity' => 1,
                ];
            }
        }

        $this->deck_data = $deckData;
        $this->save();
    }
}
