<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Category::class, function (Faker $faker) use ($factory) {
    return [
        'uuid' => $faker->uuid,
        'name' => $faker->text(20)
    ];
});
