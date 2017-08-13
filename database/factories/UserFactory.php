<?php

use Minhbang\User\User;
use Minhbang\User\Group;
use Faker\Generator;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(User::class, function (Generator $faker) {
    static $password;
    static $group_id;
    if (is_null($group_id)) {
        $group = Group::findBy('system_name', 'normal');
        $group = $group ?: factory(Group::class)->states('normal')->create();
        $group_id = $group->id;
    }

    return [
        'name' => $faker->name,
        'username' => $faker->userName,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('123456'),
        'group_id' => $group_id,
        'remember_token' => str_random(10),
    ];
});

