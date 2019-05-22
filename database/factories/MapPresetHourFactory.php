<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\MapPresetHours::class, function (Faker $faker) use ($factory) {
    return [
        'map_preset_id' => $factory->create(App\Models\MapPreset::class)
    ];
});
