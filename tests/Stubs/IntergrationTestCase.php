<?php namespace Minhbang\User\Tests\Stubs;

use Minhbang\User\User;

/**
 * Class IntergrationTestCase
 * @package Minhbang\User\Tests\Stubs
 * @author Minh Bang
 */
class IntergrationTestCase extends TestCase
{
    /**
     * @var \Minhbang\User\User[]
     */
    protected $users;

    public function setUp()
    {
        parent::setUp();
        $this->users['user'] = factory(User::class)->create();
        $this->users['admin'] = factory(User::class)->states('admin')->create();
        $this->users['super_admin'] = factory(User::class)->states('super_admin')->create();
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

    /**
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return array_merge(
            parent::getPackageProviders($app),
            [
                \Minhbang\Setting\ServiceProvider::class,
            ]
        );
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        $app['config']->set('user.middlewares', [
            'user' => ['web', 'role:sys.admin'],
            'group' => ['web', 'role:sys.admin'],
        ]);
    }
}