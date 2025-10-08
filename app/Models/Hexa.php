<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hexa extends Model
{
    protected $table = 'hexas';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'game_id',
        'name',
        'description',
        'image',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
