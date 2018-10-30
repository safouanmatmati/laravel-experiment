<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Models\Product::class, function (Faker\Generator $faker) {
    return [
        'id'          => $faker->unique()->slug(2, true),
        'name'        => join(' ', $faker->words),
        'price'       => $faker->randomFloat(2, 1),
        'description' => $faker->text,
        'brand_id'    => function () {
            return factory(App\Models\Brand::class)->create()->id;
        }
    ];
});
