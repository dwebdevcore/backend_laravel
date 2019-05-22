<?php

use Faker\Generator as Faker;

$factory->define(App\Models\BusinessPost::class, function (Faker $faker) use ($factory) {
    return [
        'uuid'        => $faker->uuid,
        'business_id' => $factory->create(App\Models\Business::class)->id,
        'user_id'     => $factory->create(App\Models\User::class)->id,
        'expire_date' => $faker->dateTimeBetween('+1 day', '+5 days')->format("Y-m-d"),
        'text'        => $faker->text(50),
        'meta'        => $faker->text(50),
    ];
});
