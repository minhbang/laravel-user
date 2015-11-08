<?php
namespace Minhbang\LaravelUser;

use Laracasts\Presenter\PresentableTrait;
use Minhbang\LaravelKit\Extensions\NestedSetModel;

/**
 * Minhbang\LaravelUser\Group
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
 * @property-read \Minhbang\LaravelUser\Group $parent
 * @property-read \Minhbang\LaravelUser\User[] $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\Minhbang\LaravelUser\Group[] $children
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\Group whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\Group whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\Group whereLft($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\Group whereRgt($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\Group whereDepth($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\Group whereSystemName($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\Group whereFullName($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\Group whereShortName($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\Group whereAcronymName($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\Group whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\Group wherePriority($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\Group whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\Group systemName($system_name)
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutNode($node)
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutSelf()
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutRoot()
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node limitDepth($limit)
 */
class Group extends NestedSetModel
{
    use PresentableTrait;
    protected $table = 'user_groups';
    protected $presenter = 'Minhbang\LaravelUser\GroupPresenter';
    protected $fillable = ['system_name', 'full_name', 'short_name', 'acronym_name'];
    public $timestamps = false;

    /**
     * @return \Minhbang\LaravelKit\Extensions\HasManyNestedSet
     */
    public function users()
    {
        return $this->hasManyNestedSet('Minhbang\LaravelUser\User');
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
        return static::system_name($system_name)->first();
    }
}