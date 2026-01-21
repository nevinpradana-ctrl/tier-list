<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Character extends Model
{
    protected $table = 'characters';
    protected $fillable = ['game_id', 'name', 'rarity', 'element_id', 'role_id', 'image_url'];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function element(): BelongsTo
    {
        return $this->belongsTo(Element::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function tierData(): HasMany
    {
        return $this->hasMany(TierData::class);
    }
}
