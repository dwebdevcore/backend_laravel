<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Traits\HasUuid;

class BusinessReviewImage extends Model
{
	use HasUuid;

    protected $fillable = ['path'];
    protected $hidden = ['uuid'];

    /**
     * @param $value
     * @return mixed
     */
    public function getPathAttribute($value) {
        return Storage::disk('s3:images')->url($value);
    }
}
