<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class OwnershipRequest extends Model
{
    protected $fillable = ['user_id', 'business_id', 'method', 'address', 'token', 'user_info', 'confirmed_at'];

    protected $casts = [
        'user_info' => 'array',
    ];

    public function scopeIsNotConfirmed($query)
    {
        return $query->whereRaw('confirmed_at IS NULL');
    }

    public function scopeIsNotExpired($query)
    {
        return $query->where('created_at', '>=', Carbon::now()->subMinutes(config('ownership.token_lifetime')));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function business() {
        return
            $this->belongsTo(Business::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return
            $this->belongsTo(User::class);
    }

    public function confirmRequest() {
        Ownerships::create([
           'business_id' => $this->business_id,
           'user_id'     => $this->user_id,
           'request_id'  => $this->id
        ]);
        $this->confirmed_at = Carbon::now();
        $this->save();
    }
}
