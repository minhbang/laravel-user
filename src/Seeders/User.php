<?php
namespace Minhbang\User\Seeders;

use DB;
use Minhbang\User\User as Model;
use Minhbang\User\Group as GroupModel;

/**
 * Class User
 *
 * @package Minhbang\User\Seeders
 */
class User
{
    /**
     * @param array $data
     */
    protected function seedUser($data)
    {
        if (isset($data['group'])) {
            if (is_string($data['group'])) {
                $group = GroupModel::findBySystemName($data['group']);
                $group_id = $group ? $group->id : 2;
            } else {
                $group_id = $data['group'];
            }
        } else {
            $group_id = 2;
        }

        $user = Model::create([
            'name'           => $data[0],
            'username'       => $data[1],
            'email'          => "{$data[1]}@domain.com",
            'password'       => isset($data['pass']) ? $data['pass'] : '123456',
            'remember_token' => null,
            'group_id'       => $group_id,
        ]);

        if (isset($data['role'])) {
            DB::table('role_user')->insert([
                'user_id'    => $user->id,
                'role_group' => $data['role'][0],
                'role_name'  => $data['role'][1],
            ]);
        }
    }

    /**
     * @param array $users
     */
    public function seed($users = [])
    {
        DB::table('users')->truncate();
        DB::table('role_user')->truncate();
        foreach ($users as $user) {
            $this->seedUser($user);
        }
    }
}