<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;
use ScoutElastic\Searchable;

class BusinessAttribute extends Model
{
    use HasUuid, Searchable;

    protected $fillable = ['business_id', 'key', 'value'];
    protected $hidden = ['uuid'];

    protected $indexConfigurator = \App\Elastic\Configurators\BusinessAttribute::class;

    protected $mapping = [
        'properties' => [
            'business_id' => [
                'type' => 'integer',
            ],
            'key'         => [
                'type'  => 'keyword',
                'index' => 'true',
            ],
            'value'       => [
                'type'  => 'keyword',
                'index' => 'true'
            ],
            'location'          => [
                'type'  => 'geo_point',
                'index' => 'true'
            ],
        ]
    ];

    public function toSearchableArray()
    {
        return [
            'business_id' => $this->business_id,
            'key'         => $this->key,
            'value'       => $this->value,
            'location'    => [
                'lat' => $this->business->lat,
                'lon' => $this->business->lng
            ],
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function business() {
        return $this->belongsTo(Business::class);
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function label() {
        return $this->hasOne(Label::class, 'key', 'key');
    }
}
