<?php

use Faker\Generator as Faker;

$factory->define(App\Models\BusinessAttribute::class, function (Faker $faker) use ($factory) {
    return [
        'uuid'        => $faker->uuid,
        'business_id' => $factory->create(App\Models\Business::class)->id,
        'key'         => $faker->word,
        'value'       => $faker->word
    ];
});
