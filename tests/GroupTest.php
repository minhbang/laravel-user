<?php
use Minhbang\User\Group;
use Minhbang\User\Seeders\UserTestData;

/**
 * Class GroupTest
 */
class GroupTest extends TestCase
{
    use UserTestData;

    public function testCreateGroups()
    {
        foreach ($this->groups as $group) {
            $this->assertTrue($group instanceof Group);
        }
    }
}