<?php namespace Minhbang\User\Tests;

use Minhbang\User\Group;
use Minhbang\User\Seeders\Group as GroupSeeder;
use Minhbang\User\Seeders\User as UserSeeder;
use Minhbang\User\Tests\Stubs\TestCase;
use Minhbang\User\User;

/**
 * Class SeederTest
 * @package Minhbang\User\Tests
 * @author Minh Bang
 */
class SeederTest extends TestCase
{
    public function test_Seed_Group()
    {
        $seeder = new GroupSeeder();
        $seeder->seed([
            'system' => [
                'Đặc biệt' => [],
            ],
            'normal' => [
                'Đơn vị 1' => [],
                'Đơn vị 2' => [
                    'items' => [
                        'Group 1.1' => [],
                    ]
                ],
                'Đơn vị 3' => [],
            ],
        ]);
        $this->assertDatabaseHas('user_groups', ['system_name' => 'system', 'full_name' => 'system']);
        $this->assertDatabaseHas('user_groups', ['system_name' => 'dac-biet', 'full_name' => 'Đặc biệt']);
        $this->assertDatabaseHas('user_groups', ['system_name' => 'don-vi-3', 'full_name' => 'Đơn vị 3']);
        $this->assertDatabaseHas('user_groups', ['system_name' => 'group-1-1', 'full_name' => 'Group 1.1']);
        $this->assertFalse(is_null(Group::findBy('system_name', 'system')));
    }

    public function test_Seed_User()
    {
        (new GroupSeeder())->seed([
            'system' => [
                'Staff' => [],
            ],
            'normal' => [
                'Member' => [],
            ],
        ]);
        $staffGroup = Group::findBy('system_name', 'staff');
        $memberGroup = Group::findBy('system_name', 'member');
        (new UserSeeder())->seed([
            ['Super Administrator', 'admin', 'role' => ['sys', 'sadmin'], 'group' => $staffGroup->id],
            ['Nguyễn Văn Anh', 'user1', 'group' => $memberGroup->id],
            ['Phạm Văn Bảo', 'user2', 'group' => 'member'],
        ]);

        $this->assertDatabaseHas('users', ['username' => 'admin']);
        $this->assertDatabaseHas('users', ['username' => 'user1']);
        $this->assertDatabaseHas('users', ['username' => 'user2']);

        $this->assertTrue(User::findBy('username', 'admin')->group->system_name == 'staff');
        $this->assertTrue(User::findBy('username', 'user1')->group->system_name == 'member');
        $this->assertTrue(User::findBy('username', 'user2')->group->system_name == 'member');
    }
}