<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Traits\HasUuid;

class BusinessPostImage extends Model
{
	use HasUuid;

    protected $fillable = ['path'];
    protected $appends  = ['url'];
    protected $hidden   = ['uuid'];

    /**
     * @return mixed
     */
    public function getUrlAttribute() {
        return Storage::disk('remote')->url($this->path);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function labels() {
        return
            $this->hasMany(BusinessPostImageLabel::class);
    }
}
