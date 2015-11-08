<?php
namespace Minhbang\LaravelUser;
/**
 * Class UserManager
 *
 * @package Minhbang\LaravelUser
 */
class UserManager
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
     * UserManager constructor.
     *
     * @param array $group_types
     * @param int $group_max_depth
     */
    public function __construct($group_types = ['system', 'normal'], $group_max_depth = 5)
    {
        foreach ($group_types as $type) {
            $this->group_types[$type] = trans("user::group.type_{$type}");
        }
        $this->group_max_depth = $group_max_depth;
    }


    /**
     * Lấy group manager của $type
     *
     * @param string|null $type
     */
    public function groups($type = null)
    {
        $type = $type ?: 'normal';
        if (!isset($this->group_types[$type])) {
            abort(500, trans('user::group.invalid_group_type'));
        }
        if (!isset($this->group_managers[$type])) {
            $this->group_managers[$type] = new GroupManager($type, $this->group_max_depth);
        }
        return $this->group_managers[$type];
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