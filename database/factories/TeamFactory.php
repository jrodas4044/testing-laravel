<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Team::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'size' => 5
    ];
});
