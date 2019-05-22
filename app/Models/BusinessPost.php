<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use ScoutElastic\Searchable;

class BusinessPost extends Model
{
    use HasUuid, Searchable;

    protected $fillable = ['business_id', 'user_id', 'expire_date', 'text', 'meta'];

    protected $casts = [
        'expire_date'  => 'date'
    ];

    protected $with   = ['images'];
    protected $hidden = ['uuid'];

    protected $indexConfigurator = \App\Elastic\Configurators\BusinessPost::class;

    public function toSearchableArray()
    {
        return [
            'id'            => $this->uuid,
            'business_name' => $this->business->name,
            'business_id'   => $this->business->uuid,
            'user_id'       => $this->user_id,
            'type'          => 'post',
            'images'        => $this->getImages(),
            'location'      => [
                'lat' => $this->business->lat,
                'lon' => $this->business->lng
            ],
            'text'          => $this->text,
            'meta'          => $this->meta,
            'expire_date'   => isset($this->expire_date) ?? $this->expire_date,
            'hours'         => $this->business->hours
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getImages()
    {
        return $this
                ->hasMany(BusinessPostImage::class)
                ->get(['path'])
            ;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images() {
        return $this->hasMany(BusinessPostImage::class);
    }

    public function coverImages() {
        return $this->images()->where('cover', true);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function business() {
        return $this->belongsTo(Business::class);
    }

    /**
     * @param $request
     * @return mixed
     */
    public function createImage($request) {
        return $this->images()->create($request);
    }

    public function getImagePathAttribute() {
        if ( count($this->images) ) {
            return $this->images->first()->path;
        }
    }

}
