<?php
namespace App\Traits;
use App\Rules\Uuid;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

/**
 * Created by PhpStorm.
 * User: byabuzyak
 * Date: 10/15/18
 * Time: 3:49 PM
 */

trait HasUuid {
    protected static function boot() {
        parent::boot();

        static::saving(function ($model) {
        	if( is_null($model->uuid) )
        	{
            	$model->uuid = Str::uuid()->toString();
        	}
        });
    }

    /**
     * @param $query
     * @param $uuid
     * @return mixed
     */
    public function scopeUuid($query, $uuid)
    {
        if (!(new Uuid())->passes('uuid', $uuid)) {
            throw (new ModelNotFoundException)->setModel(get_class($this));
        }

        return $query->where('uuid', $uuid)->firstOrFail();
    }
}
