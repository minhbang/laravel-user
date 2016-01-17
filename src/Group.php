<?php
namespace Minhbang\User;

use Laracasts\Presenter\PresentableTrait;
use Minhbang\LaravelKit\Extensions\NestedSetModel;

/**
 * Minhbang\User\Group
 *
 * @property integer $id
 * @property integer $parent_id
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property string $system_name
 * @property string $full_name
 * @property string $short_name
 * @property string $acronym_name
 * @property-read string $type
 * @property-read string $type_name
 * @property-read \Minhbang\User\Group $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Minhbang\Category\Item[] $categories
 * @property-read \Illuminate\Database\Eloquent\Collection|\Minhbang\User\User[] $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\Minhbang\User\Group[] $children
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\Group whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\Group whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\Group whereLft($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\Group whereRgt($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\Group whereDepth($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\Group whereSystemName($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\Group whereFullName($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\Group whereShortName($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\Group whereAcronymName($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\Group whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\Group wherePriority($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\Group whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\Group systemName($system_name)
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutNode($node)
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutSelf()
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutRoot()
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node limitDepth($limit)
 */
class Group extends NestedSetModel
{
    use PresentableTrait;
    protected $table = 'user_groups';
    protected $presenter = 'Minhbang\User\GroupPresenter';
    protected $fillable = ['system_name', 'full_name', 'short_name', 'acronym_name'];
    public $timestamps = false;

    /**
     * Users trực tiếp ($immediate) hay tòan bộ (bao gồm các group con)
     *
     * @param bool $immediate
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\Minhbang\LaravelKit\Extensions\HasManyNestedSet
     */
    public function users($immediate = false)
    {
        $model = config('auth.providers.users.model');
        return $immediate ? $this->hasMany($model) : $this->hasManyNestedSet($model);
    }

    /**
     * Danh sách categories group được phép quản lý
     *
     * @param bool $immediate
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\Illuminate\Database\Query\Builder
     */
    public function categories($immediate = false)
    {
        // Categories trực tiếp ($immediate) (được gán qua categories.moderator_id)
        /** @var \Illuminate\Database\Eloquent\Relations\HasMany|\Illuminate\Database\Query\Builder $query */
        $query = $this->hasMany('Minhbang\Category\Item', 'moderator_id');
        if (!$immediate) {
            /** @var \Minhbang\Category\Item[] $categories */
            $categories = $this->categories(true)->get();
            $ids = [];
            // Lấy IDs các categories con của các categories trực tiếp
            foreach ($categories as $category) {
                $ids = array_merge($ids, $category->descendants()->lists('id'));
            }
            if ($ids) {
                $query->orWhereIn('categories.id', $ids);
            }
        }
        return $query;
    }

    /**
     * @return string
     */
    public function getTypeAttribute()
    {
        return $this->exists ? $this->getRoot()->system_name : null;
    }

    /**
     * @return string
     */
    public function getTypeNameAttribute()
    {
        $type = $this->getTypeAttribute();
        return $type ? app('user-manager')->groupTypeNames($type, null) : null;
    }

    /**
     * @param \Illuminate\Database\Query\Builder|static $query
     * @param string $system_name
     *
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function scopeSystemName($query, $system_name)
    {
        return $query->where('system_name', $system_name);
    }

    /**
     * @param string $system_name
     *
     * @return static|null
     */
    public static function findBySystemName($system_name)
    {
        return static::systemName($system_name)->first();
    }

    /**
     * Là có quan quản lý của danh mục $category
     * - Được giao quản lý danh mục cha (root1, depth = 1) của $category
     *
     * @param \Minhbang\Category\Item $category
     *
     * @return bool
     */
    public function isModeratorOf($category)
    {
        if ($this->exists) {
            /** @var \Minhbang\Category\Item $category_root */
            $category_root = $category->getRoot1();
            return $category_root && $category_root->moderator_id == $this->id;
        } else {
            return false;
        }
    }
}