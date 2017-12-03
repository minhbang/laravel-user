<?php

use Faker\Generator;
use Minhbang\User\Group;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Group::class, function (Generator $faker) {
    $name = $faker->word;

    return [
        'system_name' => $name,
        'full_name' => $name,
    ];
});

$factory->state(Group::class, 'system', function () {
    return [
        'system_name' => 'system',
    ];
});

$factory->state(Group::class, 'normal', function () {
    return [
        'system_name' => 'normal',
    ];
});