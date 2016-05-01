<?php
namespace Minhbang\User;

use DB;

/**
 * Class Role
 *
 * @package Minhbang\User
 */
class Role
{
    /**
     * @var string
     */
    protected $table = 'role_user';
    /**
     * @var \Minhbang\User\User[]|\Illuminate\Database\Eloquent\Collection
     */
    protected $users;
    /**
     * @var int
     */
    protected $count_users = -1;

    /**
     * @var string
     */
    protected $group;
    /**
     * @var string
     */
    protected $group_title;
    /**
     * @var string
     */
    protected $name;

    /**
     * = group.name
     *
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $title;
    /**
     * @var string
     */
    protected $full_title;
    /**
     * @var int
     */
    protected $level;
    /**
     * @var string
     */
    protected $url;

    protected $attributes = ['id', 'group', 'group_title', 'name', 'title', 'full_title', 'level', 'url'];

    /**
     * Role constructor.
     *
     * @param string $group
     * @param string $name
     * @param int $level
     */
    public function __construct($group, $name, $level)
    {
        $this->id = "{$group}.{$name}";
        $this->group = $group;
        $this->name = $name;
        $this->level = $level;
        $this->title = trans("user::role.{$this->id}");
        $this->group_title = trans("user::role.{$this->group}.title");
        $this->full_title = "{$this->title} ({$this->group_title})";
        $this->url = route('backend.role.show', ['role' => $this->id]);
    }

    /**
     * Users đã được gán role này
     *
     * @return \Minhbang\User\User[]|\Illuminate\Database\Eloquent\Collection
     */
    public function users()
    {
        if (is_null($this->users)) {
            $this->users = User::with('group')
                ->leftJoin($this->table, "{$this->table}.user_id", '=', 'users.id')
                ->where("{$this->table}.role_group", '=', $this->group)
                ->where("{$this->table}.role_name", '=', $this->name)
                ->select('users.*')->get();
        }

        return $this->users;
    }

    /**
     * Đếm số users đã được gán role này
     * @return int
     */
    public function countUsers()
    {
        if ($this->count_users < 0) {
            $this->count_users = $this->users()->count();
        }

        return $this->count_users;
    }

    /**
     * @param int $value
     */
    public function setCountUsers($value)
    {
        $this->count_users = $value;
    }

    /**
     * @param int $user_id
     *
     * @return bool
     */
    public function attachUser($user_id)
    {
        return in_array($user_id, $this->users()->pluck('id')->all()) ?
            true :
            DB::table($this->table)->insert(
                ['user_id' => $user_id, 'role_group' => $this->group, 'role_name' => $this->name]
            );
    }

    /**
     * Không có $user = detach all users
     *
     * @param int|null $user_id
     *
     * @return bool
     */
    public function detachUser($user_id = null)
    {
        $query = DB::table($this->table)
            ->where("{$this->table}.role_group", '=', $this->group)
            ->where("{$this->table}.role_name", '=', $this->name);
        if ($user_id) {
            $query->where('user_id', '=', $user_id);
        }

        return $query->delete();
    }

    /**
     * Lấy giá trị các thuộc tính ($attributes)
     *
     * @param null|string $name
     * @param mixed $default
     *
     * @return mixed
     */
    public function get($name, $default = null)
    {
        if (empty($name)) {
            return $this;
        }

        return in_array($name, $this->attributes) ? $this->{$name} : $default;
    }

    /**
     * @param string $name
     *
     * @return null|string
     */
    function __get($name)
    {
        return $this->get($name);
    }

}