<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TierCategory extends Model
{
    protected $table = 'tier_categories';
    protected $fillable = ['game_id', 'category_name'];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function tierData(): HasMany
    {
        return $this->hasMany(TierData::class);
    }
}
