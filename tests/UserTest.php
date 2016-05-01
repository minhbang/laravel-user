<?php
use Minhbang\User\User;
use Minhbang\User\Seeders\UserTestData;
/**
 * Class UserTest
 */
class UserTest extends TestCase
{
    use UserTestData;

    public function testCreateUsers()
    {
        foreach ($this->users as $user) {
            $this->assertTrue($user instanceof User);
        }
    }
}