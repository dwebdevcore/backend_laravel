<?php

use Faker\Generator as Faker;

$factory->define(App\Models\BusinessReview::class, function (Faker $faker) use ($factory) {
    return [
        'uuid'        => $faker->uuid,
        'business_id' => $factory->create(App\Models\Business::class)->id,
        'user_id'     => $factory->create(App\Models\User::class)->id,
        'code'        => $faker->randomDigitNotNull,
        'comment'     => $faker->text(50),
        'meta'        => $faker->text(50),
    ];
});
