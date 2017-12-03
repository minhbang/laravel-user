<?php

use Faker\Generator;
use Minhbang\User\Group;
use Minhbang\User\User;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(User::class, function (Generator $faker) {
    return [
        'name' => $faker->name,
        'username' => $faker->userName,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt('123456'),
        'group_id' => Group::firstOrCreate(['system_name' => 'normal', 'full_name' => 'normal'])->id,
        'remember_token' => str_random(10),
    ];
});

$factory->state(User::class, 'super_admin', function () {
    return [
        'username' => 'admin',
        'group_id' => Group::firstOrCreate(['system_name' => 'system', 'full_name' => 'system'])->id,
    ];
});
$factory->state(User::class, 'admin', function () {
    return [
        'group_id' => Group::firstOrCreate(['system_name' => 'system', 'full_name' => 'system'])->id,
    ];
});