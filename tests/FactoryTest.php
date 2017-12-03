<?php namespace Minhbang\User\Tests;

use Minhbang\User\Tests\Stubs\TestCase;
use Minhbang\User\User;

/**
 * Class FactoryTest
 */
class FactoryTest extends TestCase
{
    /**
     * @var array
     */
    protected $users = [];

    public function setUp()
    {
        parent::setUp();
        $this->users['user'] = factory(User::class)->create();
        $this->users['admin'] = factory(User::class)->create();
        $this->users['super_admin'] = factory(User::class)->create(['username' => 'admin']);
        $this->app['db']->table('role_user')->insert([
            [
                'user_id' => $this->users['admin']->id,
                'role_group' => 'sys',
                'role_name' => 'admin',
            ],
            [
                'user_id' => $this->users['super_admin']->id,
                'role_group' => 'sys',
                'role_name' => 'sadmin',
            ],
        ]);

    }

    public function testCreateUser()
    {
        $this->assertTrue($this->users['user'] instanceof User);
    }

    public function testSaveUser()
    {
        $this->assertDatabaseHas('users', ['username' => $this->users['user']->username]);
    }

    public function testAttachRole()
    {
        $this->assertDatabaseHas('role_user', [
            'user_id' => $this->users['admin']->id,
            'role_group' => 'sys',
            'role_name' => 'admin',
        ]);
    }
}