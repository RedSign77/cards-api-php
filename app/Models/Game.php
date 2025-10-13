<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewGameAdded;

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

    public function hexas()
    {
        return $this->hasMany(Hexa::class);
    }

    public function figures()
    {
        return $this->hasMany(Figure::class);
    }

    /**
     * Boot method to register model events
     */
    protected static function booted(): void
    {
        static::created(function (Game $game) {
            // Send notification to admin email about new game
            if (config('mail.enabled', true)) {
                Notification::route('mail', config('mail.admin_address'))
                    ->notify(new NewGameAdded($game));
            }
        });
    }
}
