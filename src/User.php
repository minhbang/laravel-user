<?php
namespace Minhbang\User;

use Minhbang\Kit\Extensions\Model;
use Minhbang\Kit\Traits\Model\DatetimeQuery;
use Minhbang\Kit\Traits\Model\SearchQuery;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Laracasts\Presenter\PresentableTrait;
use DB;

/**
 * Class User
 *
 * @package Minhbang\User
 * @property integer $id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property string $password
 * @property integer $group_id
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read mixed $code
 * @property-read \Minhbang\User\Group $group
 * @property-read mixed $type
 * @property-read mixed $type_name
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User whereUsername($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User whereGroupId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User forSelectize($ignore = null, $take = 50)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User adminFirst()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User inGroup($group = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User withGroup()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Kit\Extensions\Model except($id = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Kit\Extensions\Model whereAttributes($attributes)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Kit\Extensions\Model findText($column, $text)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User orderCreated($direction = 'desc')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User orderUpdated($direction = 'desc')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User period($start = null, $end = null, $field = 'created_at', $end_if_day = false, $is_month = false)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User today($field = 'created_at')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User yesterday($same_time = false, $field = 'created_at')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User thisWeek($field = 'created_at')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User thisMonth($field = 'created_at')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User searchKeyword($keyword, $columns = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User searchWhere($column, $operator = '=', $fn = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User searchWhereIn($column, $fn)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User searchWhereBetween($column, $fn = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\User\User searchWhereInDependent($column, $column_dependent, $fn, $empty = [])
 * @mixin \Eloquent
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    use DatetimeQuery;
    use SearchQuery;
    use PresentableTrait;

    protected $presenter = Presenter::class;
    protected $table = 'users';
    protected $table_group = 'user_groups';
    protected $fillable = ['name', 'username', 'email', 'password', 'group_id', 'roles'];
    protected $hidden = ['password', 'remember_token'];

    /**
     * Cached roles
     *
     * @var array
     */
    protected $roles;

    /**
     * @var array
     */
    protected $new_roles = [];

    /**
     * Setter $this->roles = $value
     *
     * @param array $value
     */
    public function setRolesAttribute($value)
    {
        $this->new_roles = (array)$value;
    }

    public function syncNewRoles()
    {
        if ($this->new_roles) {
            foreach ($this->new_roles as $role) {
                $this->attachRole($role);
            }
        }
    }

    protected static function boot()
    {
        parent::boot();
        static::saved(function ($model) {
            $model->syncNewRoles();
        });
    }


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
     * Là super admin
     *
     * @return bool
     */
    public function isSysSadmin()
    {
        return $this->exists && $this->username === 'admin';
    }

    /**
     * Thuộc nhóm Administrator: là Super Admin hoặc được gán role 'admin'
     *
     * @return bool
     */
    public function inAdminGroup()
    {
        return $this->exists && ($this->isSysSadmin() || $this->isOne('sys.admin'));
    }

    /**
     * Thuộc BGH, roles: hieu_truong, hieu_pho, chinh_uy
     *
     * @return bool
     */
    public function inBgh()
    {
        return $this->exists && $this->isOne('hieu_*|chinh_uy');
    }

    /**
     * Có phải là người tạo $model không?
     *
     * @param mixed $model
     *
     * @return bool
     */
    public function isAuthorOf($model)
    {
        return $model->user_id && ($this->id === $model->user_id);
    }

    /**
     * @param string $value
     */
    public function setPasswordAttribute($value)
    {
        // Bỏ qua password trống
        if (!empty($value)) {
            $this->attributes['password'] = bcrypt($value);
        }
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

    // USER ROLE
    //----------------------------------------------------------------------------------
    /**
     * Lấy tất cả roles của $user
     *
     * @return array
     */
    public function roles()
    {
        if (is_null($this->roles)) {
            $this->roles = \RoleManager::getUserRoles($this->id);
        }

        return $this->roles;
    }

    /**
     * Check if the user has a role or roles.
     * $role dạng string: có thể nhiều roles phân cách bằng dấu ',' hoặc '|'
     *
     * @param int|string|array $role
     * @param bool $all
     * @param bool $exact
     *
     * @return bool
     */
    public function is($role, $all = false, $exact = false)
    {
        if (!$this->exists) {
            return false;
        }

        return $this->{$this->getMethodName('is', $all)}($role, $exact);
    }

    /**
     * Check if the user has at least one role.
     *
     * @param int|string|array $role
     * @param bool $exact
     *
     * @return bool
     */
    public function isOne($role, $exact = false)
    {
        if (!$this->exists) {
            return false;
        }
        foreach ($this->getArrayFrom($role) as $role) {
            if ($this->hasRole($role, $exact)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the user has all roles.
     *
     * @param int|string|array $role
     * @param bool $exact
     *
     * @return bool
     */
    public function isAll($role, $exact = false)
    {
        if (!$this->exists) {
            return false;
        }
        foreach ($this->getArrayFrom($role) as $role) {
            if (!$this->hasRole($role, $exact)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Kiểm tra $user có $role.
     * - Có thể sử dụng str*, vd: 'bgh.*' tất cả role thuộc Ban giám hiệu
     * - Mặc định không kiểm tra 'chính xác', nghĩa là role có level cao hơn sẽ 'bao gồm | là' role thấp
     *
     * @param int|string $role
     * @param bool $exact
     *
     * @return bool
     */
    public function hasRole($role, $exact = false)
    {
        if (!$this->exists || empty($role)) {
            return false;
        }
        // super admin 'toàn quyền'
        if ($this->isSysSadmin() && !$exact) {
            return true;
        }
        /**
         * kiểm tra 'không chính xác' khi
         * ($exact == false) và ($role không có dạng 'group.name')
         */
        $not_exact = !$exact && \RoleManager::validate($role);
        // Duyệt tất cả các roles hiện có của user
        foreach ($this->roles() as $r) {
            if (
                // được gán
                str_is($role, $r) ||
                // cùng group, nhưng được gán role có level cao hơn $role
                ($not_exact && \RoleManager::isHigherLevel($r, $role))
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $group
     * @param string $name
     *
     * @return bool
     */
    public function attachRole($group, $name = null)
    {
        if (is_null($name)) {
            list($group, $name) = explode('.', $group, 2);
        }
        $level = config("user.roles.{$group}.{$name}");
        if (!$level || !$this->exists) {
            return false;
        } else {
            if (DB::table('role_user')
                ->where('user_id', $this->id)
                ->where('role_group', $group)
                ->where('role_name', $name)
                ->count()
            ) {
                return true;
            } else {
                return DB::table('role_user')
                    ->insert(['user_id' => $this->id, 'role_group' => $group, 'role_name' => $name]);
            }
        }
    }

    /**
     * Hàm 'động' kiểm tra role, vd:
     * $user có role 'sys.admin', check: $user->isSysAdmin() => true
     * $user có role 'sys.sadmin', check: $user->isSysSadmin() => true
     *
     * @param string $method
     * @param array $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (starts_with($method, 'is') && !method_exists($this, $method)) {
            if ($parameters) {
                $all = $parameters[0];
                $exact = isset($parameters[1]) ? $parameters[1] : false;
            } else {
                $all = $exact = false;
            }

            return $this->is(snake_case(substr($method, 2), '.'), $all, $exact);
        }

        return parent::__call($method, $parameters);
    }


    /**
     * Get method name.
     *
     * @param string $name
     * @param bool $all
     *
     * @return string
     */
    protected function getMethodName($name, $all)
    {
        return ((bool)$all) ? $name . 'All' : $name . 'One';
    }

    /**
     * Tách $argument dạng 'string' (phân các bằng dấu ',' hoặc dấu '|') thành 'array'
     *
     * @param int|string|array $argument
     *
     * @return array
     */
    protected function getArrayFrom($argument)
    {
        return (!is_array($argument)) ? preg_split('/ ?[,|] ?/', $argument) : $argument;
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
        return $this->belongsTo('Minhbang\User\Group');
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

        return $query->with('group')
            ->whereIn("{$this->table}.group_id", $ids);
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
     * Là thủ trưởng một cơ quan, đơn vị
     * - Thuộc user_group (cơ quan, đơn vị) chính, depth = 1
     * - Và được gán Role 'Thủ trường', 'truong_*' hoặc 'pho_*', vd; truong_phong, pho_phong,...
     *
     * @param string|int $roles Roles để xác
     *
     * @return bool
     */
    /*public function isGroupManager($roles = 'truong_*|pho_*')
    {
        return $this->exists && $this->group && $this->group->depth == 1 && $this->isOne($roles);
    }*/

    /**
     * Là Thủ trưởng đơn vị của $someone?
     * - Là thủ trưởng cơ quan, đơn vị (chính)
     * - Và cùng đơn vị hoặc thuộc đơn vị cấp trên của $someone
     *
     * @param static|int $someone
     *
     * @return bool
     */
    /*public function isManagerOf($someone)
    {
        return $this->isGroupManager() && $someone->group && $this->group->isSelfOrAncestorOf($someone->group);
    }*/

    /**
     * Là người quản lý danh mục $category
     * - Là thủ trưởng một cơ quan, đơn vị
     * - Cơ quan, đơn vị đơn vị đó phải quản lý $category
     *
     * @param \Minhbang\Category\Category $category
     *
     * @return bool
     */
    /*public function isModeratorOf($category)
    {
        return $this->isGroupManager() && $this->group->isModeratorOf($category);
    }*/
}
