<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $table = 'cards';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $casts = [
        'card_data' => 'array',
    ];

    protected $fillable = [
        'game_id',
        'type_id',
        'name',
        'image',
        'card_text',
        'card_data',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function cardType()
    {
        return $this->belongsTo(CardType::class, 'type_id');
    }
}
