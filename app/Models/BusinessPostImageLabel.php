<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessPostImageLabel extends Model
{
    protected $fillable = ['label', 'confidence', 'src', 'cat_0', 'cat_1', 'cat_2', 'cat_3', 'cat_4', 'cat_5', 'cat_6'];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function businessImage() {
        return $this->belongsTo(BusinessPostImage::class);
    }
}
