<?php namespace Minhbang\User\Tests;

use Minhbang\User\Group;
use Minhbang\User\Tests\Stubs\TestCase;

/**
 * Class GroupTest
 * @package Minhbang\User\Tests
 * @author Minh Bang
 */
class GroupTest extends TestCase
{
    protected $typeRoot;

    public function setUp()
    {
        parent::setUp();
        $this->typeRoot = app('user-manager')->groups('normal')->typeRoot();
    }

    public function test_Creat_Group()
    {
        $group = new Group();
        $group->fill([
            'system_name' => 'group-1',
            'full_name' => 'Group 1',
        ]);
        $group->save();
        $group->makeChildOf($this->typeRoot);
        $this->assertDatabaseHas('user_groups', [
            'system_name' => 'group-1',
            'full_name' => 'Group 1',
        ]);
    }

    public function test_Group_Meta_Data()
    {
        config(['user.group_meta.attributes' => [
            'extra1' => '',
            'extra2' => '',
        ]]);
        $group = new Group();
        $group->fill([
            'system_name' => 'group-2',
            'full_name' => 'Group 2',
            'extra1' => 111,
            'extra2' => 'Extra Info',
            'extra3' => 'No',
        ]);
        $group->save();
        $model = Group::findBy('system_name', 'group-2');
        $this->assertFalse(is_null($model));
        $this->assertTrue($model->extra1 === 111);
        $this->assertTrue($model->extra2 === 'Extra Info');
        $this->assertTrue(is_null($model->extra3));
    }
}