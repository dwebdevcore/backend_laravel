<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\StickerCategory::class, function (Faker $faker) {
    return [
        'name' => $faker->word
    ];
});
