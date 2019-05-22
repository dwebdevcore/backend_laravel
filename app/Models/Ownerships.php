<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ownerships extends Model
{
    protected $fillable = ['user_id', 'business_id', 'request_id'];
}
