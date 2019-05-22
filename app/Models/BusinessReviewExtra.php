<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class BusinessReviewExtra extends Model
{
	use HasUuid;
    protected $hidden = ['uuid'];
}
