<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StickerCategory extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function stickers() {
        return $this->belongsToMany(Sticker::class);
    }
}
