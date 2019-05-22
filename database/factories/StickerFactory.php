<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Sticker::class, function (Faker $faker) {
    return [
        'image' => $faker->image('/tmp/',400,300, null, false),
        'tags'  => $faker->word
    ];
});
