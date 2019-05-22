<?php

use Faker\Generator as Faker;

$factory->define(App\Models\MapPreset::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'uuid' => $faker->uuid,
    ];
});
