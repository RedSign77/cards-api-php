<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Figure extends Model
{
    protected $table = 'figures';
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
