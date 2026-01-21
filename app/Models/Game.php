<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    protected $table = 'games';
    protected $fillable = ['game_name', 'slug', 'icon_url', 'description'];

    public function elements(): HasMany
    {
        return $this->hasMany(Element::class);
    }

    public function roles(): HasMany
    {
        return $this->hasMany(Role::class);
    }

    public function characters(): HasMany
    {
        return $this->hasMany(Character::class);
    }

    public function tierCategories(): HasMany
    {
        return $this->hasMany(TierCategory::class);
    }
}
