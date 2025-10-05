<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $table = 'games';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'creator_id',
        'name',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function cardTypes()
    {
        return $this->hasMany(CardType::class);
    }

    public function decks()
    {
        return $this->hasMany(Deck::class);
    }
}
