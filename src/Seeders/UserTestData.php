<?php
namespace Minhbang\User\Seeders;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Minhbang\User\User;
use Minhbang\User\Group;
use Minhbang\User\Seeders\User as UserSeeder;
use Minhbang\User\Seeders\Group as GroupSeeder;

/**
 * Class SeedTestData
 *
 * @package Minhbang\User\Seeders
 * @mixin \TestCase
 */
trait UserTestData
{
    use DatabaseMigrations;
    /**
     * @var \Minhbang\User\Group[]
     */
    protected $groups = [];

    /**
     * @var \Minhbang\User\User[]
     */
    protected $users = [];

    protected function setUp()
    {
        parent::setUp();
        (new GroupSeeder())->seed([
            'system' => [ // 1
                'Đặc biệt' => ['Đ.Biệt', 'ĐB'], //2
            ],
            'normal' => [ //3
                'Đơn vị 1' => ['Đ.Vị 1', 'ĐV1'], //4
                'Đơn vị 2' => ['Đ.Vị 2', 'ĐV2'], //5
                'Đơn vị 3' => ['Đ.Vị 3', 'ĐV3'], //6
            ],
        ]);

        (new UserSeeder())->seed([
            ['Super Administrator', 'admin', 'role' => ['sys', 'sadmin'], 'group' => 2],
            ['Administrator', 'quantri', 'role' => ['sys', 'admin'], 'group' => 2],
            ['Nguyễn Văn An', 'user1', 'group' => 4],
            ['Nguyễn Văn Anh', 'user2', 'group' => 5],
            ['Phạm Văn Bảo', 'user3', 'group' => 'don-vi-3'],
        ]);

        foreach (['admin', 'quantri', 'user1', 'user2', 'user3'] as $username) {
            $this->users[$username] = User::findBy('username', $username);
        }

        foreach (['dac-biet', 'don-vi-1', 'don-vi-2', 'don-vi-3'] as $system_name) {
            $this->groups[$system_name] = Group::findBy('system_name', $system_name);
        }
    }
}