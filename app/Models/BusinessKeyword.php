<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class BusinessKeyword extends Model
{
	use HasUuid;

    protected $fillable = ['business_id', 'keyword', 'relevance'];
    protected $hidden = ['uuid'];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function business() {
        return $this->belongsTo(Business::class);
    }
}
