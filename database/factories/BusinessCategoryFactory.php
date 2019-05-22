<?php

use Faker\Generator as Faker;

$factory->define(App\Models\BusinessCategory::class, function (Faker $faker) use ($factory) {
    return [
        'business_id' => $factory->create(App\Models\Business::class)->id,
        'category_id' => $factory->create(App\Models\Category::class)->id,
        'relevance'   => $faker->numberBetween(1, 10)
    ];
});
