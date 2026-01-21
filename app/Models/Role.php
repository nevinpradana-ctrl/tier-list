<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = ['game_id', 'role_name', 'icon_url'];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function characters(): HasMany
    {
        return $this->hasMany(Character::class);
    }
}
