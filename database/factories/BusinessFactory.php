<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Business::class, function (Faker $faker) use ($factory) {
    return [
        'user_id' => $factory->create(App\Models\User::class)->id,
        'uuid'    => $faker->uuid,
        'name'    => $faker->company,
        'lat'     => $faker->latitude,
        'lng'     => $faker->longitude
    ];
});
