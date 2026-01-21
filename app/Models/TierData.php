<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TierData extends Model
{
    protected $table = 'tier_data';
    protected $fillable = ['tier_category_id', 'character_id', 'rank', 'sort_order', 'note'];

    public function tierCategory(): BelongsTo
    {
        return $this->belongsTo(TierCategory::class);
    }

    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class);
    }
}
