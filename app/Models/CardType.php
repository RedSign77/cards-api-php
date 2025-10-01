<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardType extends Model
{
    protected $table = 'cardtypes';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = [
        'game_id',
        'name',
        'typetext',
        'description',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function cards()
    {
        return $this->hasMany(Card::class, 'type_id');
    }
}
