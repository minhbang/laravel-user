<?php

use Minhbang\User\Group;
use Faker\Generator;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Group::class, function (Generator $faker) {
    $name = $faker->word;

    return [
        'system_name' => $name,
        'full_name' => $name,
        'short_name' => $name,
        'acronym_name' => $name,
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