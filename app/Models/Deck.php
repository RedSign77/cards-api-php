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
        return $this->hasMany(User::class, 'id');
    }
}
