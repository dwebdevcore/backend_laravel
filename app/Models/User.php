<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Twilio\Rest\Client;
use App\Traits\HasUuid;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, HasUuid;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    protected $fillable = ['name', 'phone', 'gender', 'age_group'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'uuid'
    ];

    /**
     * @param string $email
     * @return mixed
     */
    public static function byEmail(string $email) {
        return static::where('email', $email)->firstOrFail();
    }

    /**
     * @param string $password
     * @return bool
     */
    public function hasPassword(string $password) {
        return Hash::check($password, $this->password);
    }

    /**
     * @return string
     */
    public function generateToken() {
        return $this->createToken('Laravel Password Grant Client')->accessToken;
    }

    /**
     * @return void
     */
    public function removeToken($id) {
        $token = $this->tokens->find($id);

        $token->revoke();
    }

    /**
     * @throws \Twilio\Exceptions\ConfigurationException
     */
    public function sendVerificationCode() {
        $client = new Client();
        $client->messages->create($this->phone, [
            'from' => config('TWILIO_NUMBER'),
            'body' => "Your verification code is: {$this->code}"
        ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews() {
        return $this->hasMany(BusinessReview::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories() {
        return
            $this->belongsToMany(Category::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function businesses() {
        return $this
                ->belongsToMany(Business::class);
    }
}
