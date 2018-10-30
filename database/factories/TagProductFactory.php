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

$factory->define(App\Models\TagProduct::class, function (Faker\Generator $faker) {
    return [
        'tag_id' => function () {
            return factory(App\Models\Tag::class)->create()->id;
        },
        'product_id' => function () {
            return factory(App\Models\Product::class)->create()->id;
        }
    ];
});
