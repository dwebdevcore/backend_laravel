<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class Category extends Model
{
    use HasUuid;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function businesses() {
        return
            $this->belongsToMany(Business::class, 'business_category', 'category_id')
                ->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users() {
        return
            $this->belongsToMany(User::class);
    }
}
