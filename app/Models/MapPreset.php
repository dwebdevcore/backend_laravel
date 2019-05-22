<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class MapPreset extends Model
{
    use HasUuid;

    protected $with = ['businessHours', 'categories'];
    protected $hidden = ['uuid'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hours() {
        return $this->hasMany(MapPresetHours::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function businessHours() {
        return $this->hasMany(MapPresetBusinessHours::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories() {
        return $this->belongsToMany(Category::class, 'map_preset_categories');
    }
}
