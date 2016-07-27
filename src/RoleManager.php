<?php
namespace Minhbang\User;

use DB;

class RoleManager
{
    /**
     * @var \Minhbang\User\Role[]
     */
    protected $roles = [];
    /**
     * Đã đếm xố lượng users chưa
     *
     * @var bool
     */
    protected $counted = false;

    /**
     * RoleManager constructor.
     *
     * @param array $all
     */
    public function __construct($all = [])
    {
        foreach ($all as $group => $roles) {
            $this->roles[$group] = [];
            foreach ($roles as $name => $level) {
                $this->roles[$group][$name] = new Role($group, $name, $level);
            }
        }
    }

    /**
     * Lấy role: $params = 'sys.admin'
     * Lấy attribute của role: $params = 'sys.admin.level'
     *
     * @param null|string $params
     * @param mixed $default
     *
     * @return null|\Minhbang\User\Role
     */
    public function roles($params = null, $default = null)
    {
        if (empty($params)) {
            return $this->roles;
        }
        $params = explode('.', $params, 3);
        if (empty($params[1])) {
            return isset($this->roles[$params[0]]) ? $this->roles[$params[0]] : $default;
        } else {
            if (isset($this->roles[$params[0]][$params[1]])) {
                return $this->roles[$params[0]][$params[1]]->get(isset($params[2]) ? $params[2] : null, $default);
            } else {
                return $default;
            }
        }
    }

    /**
     * All roles, đã đếm số users được gán
     *
     * @return \Minhbang\User\Role[]
     */
    public function countedRoles()
    {
        $roles = $this->roles;

        if (!$this->counted) {
            $counted = DB::table('role_user')
                ->select(DB::raw('role_group, role_name, count(*) as user_count'))
                ->groupBy('role_group', 'role_name')
                ->get();
            foreach ($counted as $count) {
                if (isset($this->roles[$count->role_group][$count->role_name])) {
                    $this->roles[$count->role_group][$count->role_name]->setCountUsers($count->user_count);
                }
            }
            $this->counted = true;
        }

        return $roles;
    }

    /**
     * All roles, bỏ các guarded roles
     *
     * @param null|string $params
     * @param mixed $default
     *
     * @return array|string
     */
    public function guarded($params = null, $default = null)
    {
        $roles = $this->roles;
        array_forget($roles, 'sys.sadmin');

        return array_get($roles, $params, $default);
    }

    /**
     * Kiểm tra 'input' role ID, chắc chắn có dạng: group.name
     *
     * @param string $id
     *
     * @return bool
     */
    public function validate($id)
    {
        return preg_match('/^[a-z0-9_]+\.[a-z0-9_]+$/', $id) && array_has($this->roles, $id);
    }

    /**
     * @param string $id
     *
     * @return \Minhbang\User\Role|null
     */
    public function get($id)
    {
        return $this->validate($id) ? array_get($this->roles, $id) : null;
    }

    /**
     * Lấy danh sách roles của $user_id
     *
     * @param int $user_id
     * @param bool $get_id lấy dạng role id
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserRoles($user_id, $get_id = true)
    {
        $roles = DB::table('role_user')->where('user_id', '=', $user_id)->select('role_group', 'role_name')->get();
        if ($get_id) {
            return array_map(function ($role) {
                return "{$role->role_group}.{$role->role_name}";
            }, $roles);
        } else {
            return $roles;
        }
    }

    /**
     * Role $attribute cao nhất từ danh sách $roles mà user được gán
     *
     * @param array $lists Danh sách role ID cần test
     * @param null $user_id
     * @param string $attribute
     *
     * @return null|string
     */
    public function getUserMaxRole($lists = [], $user_id = null, $attribute = 'title')
    {
        $roles = $this->getUserRoles($user_id ?: user('id'));
        foreach ($lists as $role) {
            if (in_array($role, $roles)) {
                return $this->roles("{$role}.{$attribute}");
            }
        }

        return null;
    }

    /**
     * Check ($role1, $roles cùng nhóm) VÀ ($role1.level ? $role2.level)
     *
     * @param string $role1
     * @param string $role2
     *
     * @return bool
     */
    public function isHigherLevel($role1, $role2)
    {
        list($group1,) = explode('.', $role1);
        list($group2,) = explode('.', $role2);

        return ($group1 === $group2) && ($this->roles("{$role1}.level") > $this->roles("{$role2}.level"));
    }
}