<?php
namespace Minhbang\LaravelUser;

use Minhbang\AccessControl\Traits\User\HasRole;
use Minhbang\LaravelKit\Extensions\Model;
use Minhbang\LaravelKit\Traits\Model\DatetimeQuery;
use Minhbang\LaravelKit\Traits\Model\SearchQuery;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Laracasts\Presenter\PresentableTrait;
use Minhbang\LaravelUser\Traits\HasGroup;

/**
 * Class User
 *
 * @package Minhbang\LaravelUser
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\Minhbang\AccessControl\Models\Role[] $roles
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User whereUsername($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User whereGroupId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User forSelectize($ignore = null, $take = 10)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User adminFirst()
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelKit\Extensions\Model except($id = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelKit\Extensions\Model findText($column, $text)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User orderCreated($direction = 'desc')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User orderUpdated($direction = 'desc')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User period($start = null, $end = null, $field = 'created_at', $end_if_day = false, $is_month = false)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User today($field = 'created_at')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User yesterday($same_time = false, $field = 'created_at')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User thisWeek($field = 'created_at')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User thisMonth($field = 'created_at')
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User searchWhere($column, $operator = '=', $fn = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User searchWhereIn($column, $fn)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User searchWhereBetween($column, $fn = null)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User searchWhereInDependent($column, $column_dependent, $fn, $empty = [])
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;
    use DatetimeQuery;
    use SearchQuery;
    use PresentableTrait;
    use HasRole;
    use HasGroup;

    protected $presenter = UserPresenter::class;
    protected $table = 'users';
    protected $fillable = ['name', 'username', 'email', 'password', 'group_id'];
    protected $hidden = ['password', 'remember_token'];

    /**
     * Lấy $take user phục vụ selectize user
     *
     * @param \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User $query
     * @param mixed|null $ignore
     * @param int $take
     *
     * @return \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User
     */
    public function scopeForSelectize($query, $ignore = null, $take = 50)
    {
        return $query->withGroup()->except($ignore)
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
        return static::lists($attribute, $key)->all();
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
     * Là quản trị hệ thống
     *
     * @return bool
     */
    public function isSuperadmin()
    {
        return $this->username === 'admin';
    }

    /**
     * Thuộc nhóm Administrator: là Super Admin hoặc được gán role 'admin'
     *
     * @return bool
     */
    public function inAdminGroup()
    {
        return $this->exists && ($this->isSuperadmin() || $this->isOne('admin'));
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
}
