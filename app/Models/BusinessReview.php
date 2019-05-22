<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;
use ScoutElastic\Searchable;

class BusinessReview extends Model
{
    use HasUuid, Searchable;

    protected $guarded = ['id'];
    protected $with    = ['keywords'];
    protected $hidden  = ['uuid'];

    protected $indexConfigurator = \App\Elastic\Configurators\BusinessReview::class;

    public function toSearchableArray()
    {
        return [
            'id'            => $this->uuid,
            'business_name' => $this->business->name,
            'business_id'   => $this->business->uuid,
            'user_id'       => $this->user_id,
            'type'          => 'review',
            'images'        => $this->getImages(),
            'location'      => [
                'lat' => $this->business->lat,
                'lon' => $this->business->lng
            ],
            'code'          => $this->code,
            'comment'       => $this->comment,
            'meta'          => $this->meta,
            'hours'         => $this->business->hours
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getImages()
    {
        return $this
                ->hasMany(BusinessReviewImage::class)
                ->get(['path'])
            ;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function business() {
        return $this->belongsTo(Business::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images() {
        return $this->hasMany(BusinessReviewImage::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function keywords()
    {
        return
            $this
                ->hasMany(BusinessReviewKeyword::class)
                ->orderBy('relevance', 'DESC')
            ;
    }

    /**
     * @return string
     */
    public function getKeywordsListAttribute() {
        return
            $this->hasMany(BusinessReviewKeyword::class)->pluck('keyword')->implode(', ');
    }

    /**
     * @param $data
     * @return Model
     */
    public function createImage($data) {
        return $this->images()->create($data);
    }
}
