<?php
namespace Minhbang\User;

use Minhbang\Kit\Extensions\Model;
use Minhbang\Kit\Traits\Model\DatetimeQuery;
use Minhbang\Kit\Traits\Model\SearchQuery;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Laracasts\Presenter\PresentableTrait;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class User
 *
 * @package Minhbang\User
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property string $password
 * @property int $group_id
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read string $code
 * @property-read string $type
 * @property-read string $type_name
 * @property-read \Minhbang\User\Group $group
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User adminFirst()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Kit\Extensions\Model except($ids)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User forSelectize($ignore = null, $take = 50)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User inGroup($group = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User orderCreated($direction = 'desc')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User orderUpdated($direction = 'desc')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User period($start = null, $end = null, $field = 'created_at', $end_if_day = false, $is_month = false)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User searchKeyword($keyword, $columns = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User searchWhere($column, $operator = '=', $fn = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User searchWhereBetween($column, $fn = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User searchWhereIn($column, $fn)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User searchWhereInDependent($column, $column_dependent, $fn, $empty = [])
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User thisMonth($field = 'created_at')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User thisWeek($field = 'created_at')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User today($field = 'created_at')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Kit\Extensions\Model whereAttributes($attributes)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User whereGroupId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User whereUsername($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User withGroup()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User yesterday($same_time = false, $field = 'created_at')
 * @mixin \Eloquent
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;
    use DatetimeQuery;
    use SearchQuery;
    use PresentableTrait;

    protected $presenter = Presenter::class;
    protected $table = 'users';
    protected $table_group = 'user_groups';
    protected $fillable = ['name', 'username', 'email', 'password', 'group_id'];
    protected $hidden = ['password', 'remember_token'];


    /**
     * @var Group
     */
    protected $groupRoot;

    /*protected static function boot()
    {
        parent::boot();
        static::saved(function (User $model) {
            $model->syncNewRoles();
        });
    }*/


    /**
     * Lấy $take user phục vụ selectize user
     *
     * @param \Illuminate\Database\Query\Builder|\Minhbang\User\User $query
     * @param mixed|null $ignore
     * @param int $take
     *
     * @return \Illuminate\Database\Query\Builder|\Minhbang\User\User
     */
    public function scopeForSelectize($query, $ignore = null, $take = 50)
    {
        return $query->withGroup()->except($ignore)
            ->where("{$this->table}.username", '<>', 'admin')
            ->select([
                "{$this->table}.id",
                "{$this->table}.name",
                "{$this->table}.username",
                "{$this->table_group}.full_name as group_name",
            ])->take($take);
    }

    /**
     * @param string $attribute
     * @param string $key
     *
     * @return array
     */
    public static function getList($attribute = 'title', $key = 'id')
    {
        return static::pluck($attribute, $key)->all();
    }

    /**
     * id đã mã hóa, $user->code
     *
     * @return string
     */
    public function getCodeAttribute()
    {
        return $this->id ? encode_id($this->id, 'user') : null;
    }

    /**
     * @param string $code
     *
     * @return int
     */
    public static function getIdByCode($code)
    {
        return decode_id($code, 'user');
    }

    /**
     * @param string $code
     * @param array $columns
     *
     * @return \Illuminate\Support\Collection|null|static
     */
    public static function findByCode($code, $columns = ['*'])
    {
        return static::find(static::getIdByCode($code), $columns);
    }

    /**
     * @param string $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = $value ? bcrypt($value) : $this->attributes['password'];
    }

    /**
     * Admin luôn đứng đầu
     * Chú ý gọi query này trước các quyery orderBy khác
     *
     * @param \Illuminate\Database\Query\Builder $query
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeAdminFirst($query)
    {
        return $query->orderByRaw("`users`.`username`='admin' DESC");
    }


    // USER GROUP
    //----------------------------------------------------------------------------------
    /**
     * Đơn vị của $user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
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
     * @param \Illuminate\Database\Query\Builder|\Minhbang\User\User $query
     * @param \Minhbang\User\Group $group
     *
     * @return \Illuminate\Database\Query\Builder|\Minhbang\User\User
     */
    public function scopeInGroup($query, $group = null)
    {
        if (is_null($group)) {
            return $query->with('group');
        }
        $ids = $group->descendantsAndSelf()->pluck('id');

        return $query->with('group')->whereIn("{$this->table}.group_id", $ids);
    }

    /**
     *
     * @param \Illuminate\Database\Query\Builder|\Minhbang\User\User $query
     *
     * @return \Illuminate\Database\Query\Builder|\Minhbang\User\User
     */
    public function scopeWithGroup($query)
    {
        return $query->leftJoin($this->table_group, "{$this->table_group}.id", '=', "{$this->table}.group_id");
    }

    /**
     * Là người tạo $model
     *
     * @param \Minhbang\User\Support\HasOwner $model
     *
     * @return boolean
     */
    public function isOwnerOf($model)
    {
        return $model->user_id && ($this->id === $model->user_id);
    }

    /**
     * Lấy group cao nhất (depth = 1)
     *
     * @return \Minhbang\User\Group
     */
    public function getGroupRoot()
    {
        if (is_null($this->groupRoot)) {
            $this->groupRoot = $this->group->getRoot1();
        }

        return $this->groupRoot;
    }

    /**
     * Lấy tất cả users cùng group
     *
     * @param bool $root
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSameGroupUsers($root = true)
    {
        return $this->group ?
            ($root ? $this->group->users : $this->getGroupRoot()->users) :
            new Collection();
    }

    /**
     * Lấy categories cơ quan(group) mình được giao quản lý
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function getGroupCategories()
    {
        return $this->group ?
            $this->group->getRoot1()->categories()->get() :
            new Collection();
    }

    /**
     * Lấy thư mục private của user
     *
     * @param string $path
     *
     * @return string
     */
    public function storage_path($path = '')
    {
        abort_unless($this->exists, 500, "Not call storage_path() for Guest");
        $path = data_path("{$this->username}" . ($path ? "/$path" : ''));

        return check_path($path, false, $path);
    }

    /**
     * Lấy thư mục upload (tương đối/tuyệt đối) của user
     * - Tương đối luôn luôn: /upload/<user code>/$path
     * - Tuyệt đối upload_path() .'/' . $path
     *
     * @param string $path
     * @param bool $full
     *
     * @return string
     */
    function upload_path($path = '', $full = false)
    {
        abort_unless($this->exists, 500, "Not call public_path() for Guest");
        $path = ($full ? upload_path() : '/upload') . "/{$this->code}" . ($path ? "/$path" : '');

        return $full ? check_path($path, false, $path) : $path;
    }
}
