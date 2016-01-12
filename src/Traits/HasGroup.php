<?php
namespace Minhbang\LaravelUser\Traits;
/**
 * Class HasGroup
 * Cho User model có UserGroup
 *
 * @package Minhbang\LaravelUser\Traits
 * @property string $table
 * @property bool $exists
 * @property-read mixed $type
 * @property-read mixed $type_name
 * @property-read \Minhbang\LaravelUser\Group $group
 * @method \Illuminate\Database\Eloquent\Relations\BelongsTo belongsTo($related, $foreignKey = null, $otherKey = null, $relation = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User inGroup($group = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User withGroup()
 * @method bool isOne($role)
 */
trait HasGroup
{
    /**
     * @var string
     */
    protected $table_group = 'user_groups';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo('Minhbang\LaravelUser\Group');
    }

    /**
     * Getter $user->type
     *
     * @return string
     */
    public function getTypeAttribute()
    {
        return $this->group ? $this->group->type : null;
    }

    /**
     * Getter $user->type_name
     *
     * @return string
     */
    public function getTypeNameAttribute()
    {
        return $this->group ? $this->group->type_name : null;
    }

    /**
     * Tất cả user thuộc $group và con cháu của $group
     *
     * @param \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User $query
     * @param \Minhbang\LaravelUser\Group $group
     *
     * @return \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User
     */
    public function scopeInGroup($query, $group = null)
    {
        if (is_null($group)) {
            return $query->with('group');
        }
        $ids = $group->descendantsAndSelf()->lists('id')->all();
        return $query->with('group')
            ->whereIn("{$this->table}.group_id", $ids);
    }

    /**
     *
     * @param \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User $query
     *
     * @return \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User
     */
    public function scopeWithGroup($query)
    {
        return $query->leftJoin("{$this->table_group}", "{$this->table_group}.id", '=', "{$this->table}.group_id");
    }

    /**
     * Là thủ trưởng một cơ quan, đơn vị
     * - Thuộc user_group (cơ quan, đơn vị) chính, depth = 1
     * - Và được gán Role 'Thủ trường', 'truong_*' hoặc 'pho_*', vd; truong_phong, pho_phong,...
     *
     * @param string|int $roles Roles để xác
     *
     * @return bool
     */
    public function isGroupManager($roles = 'truong_*|pho_*')
    {
        return $this->exists && $this->group && $this->group->depth == 1 && $this->isOne($roles);
    }

    /**
     * Là Thủ trưởng đơn vị của $someone?
     * - Là thủ trưởng cơ quan, đơn vị (chính)
     * - Và cùng đơn vị hoặc thuộc đơn vị cấp trên của $someone
     *
     * @param static|int $someone
     *
     * @return bool
     */
    public function isManagerOf($someone)
    {
        return $this->isGroupManager() && $someone->group && $this->group->isSelfOrAncestorOf($someone->group);
    }

    /**
     * Là người quản lý danh mục $category
     * - Là thủ trưởng một cơ quan, đơn vị
     * - Cơ quan, đơn vị đơn vị đó phải quản lý $category
     *
     * @param \Minhbang\Category\Item $category
     *
     * @return bool
     */
    public function isModeratorOf($category)
    {
        return $this->isGroupManager() && $this->group->isModeratorOf($category);
    }
}