<?php
namespace Minhbang\User;

/**
 * Class Manager
 *
 * @package Minhbang\User
 */
class Manager
{
    /**
     * @var array
     */
    protected $group_managers = [];
    /**
     * @var int
     */
    protected $group_max_depth;
    /**
     * @var array
     */
    protected $group_types = [];

    /**
     * Danh sách User models theo ID
     *
     * @var \Minhbang\User\User[]
     */
    protected $users = [];
    /**
     * @var User
     */
    protected $new_user;
    /**
     * UserManager constructor.
     *
     * @param array $group_types
     * @param int $group_max_depth
     */
    public function __construct($group_types = ['system', 'normal'], $group_max_depth = 5)
    {
        foreach ($group_types as $type) {
            $this->group_types[$type] = __("user::group.type_{$type}");
        }
        $this->group_max_depth = $group_max_depth;
    }

    /**
     * Lấy group manager của $type
     *
     * @param string|null $type
     *
     * @return \Minhbang\User\GroupManager
     */
    public function groups($type = null)
    {
        $type = $type ?: 'normal';
        if (!isset($this->group_types[$type])) {
            abort(500, __('Invalid Group type!'));
        }
        if (!isset($this->group_managers[$type])) {
            $this->group_managers[$type] = new GroupManager($type, $this->group_max_depth);
        }

        return $this->group_managers[$type];
    }

    /**
     * Danh sách cơ quan, đơn vị chính; phục vụ cho selectize
     *
     * @param string $attribute
     * @param string $key
     *
     * @return array
     */
    public function selectizeGroups($attribute = 'full_name', $key = 'id')
    {
        $lists = $this->listGroups($attribute, $key);

        return array_map(
            function ($key, $value) {
                return ['value' => $key, 'text' => $value];
            },
            array_keys($lists),
            array_values($lists)
        );
    }

    /**
     * Danh sách cơ quan, đơn vị chính
     *
     * @param string $attribute
     * @param string $key
     *
     * @return array
     */
    public function listGroups($attribute = 'full_name', $key = 'id')
    {
        return $this->groups()->listRoots($attribute, $key);
    }

    /**
     * @param string $type
     * @param mixed $default
     *
     * @return array
     */
    public function groupTypeNames($type = null, $default = false)
    {
        if ($type) {
            return isset($this->group_types[$type]) ? $this->group_types[$type] : $default;
        } else {
            return $this->group_types;
        }
    }
}