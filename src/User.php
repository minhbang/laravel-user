<?php
namespace Minhbang\LaravelUser;
use Minhbang\LaravelKit\Extensions\Model;
use Minhbang\LaravelKit\Traits\Model\DatetimeQuery;
use Minhbang\LaravelKit\Traits\Model\SearchQuery;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Laracasts\Presenter\PresentableTrait;

/**
 * LaravelUser\User
 *
 * @property integer $id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read mixed $code
 * @property-read mixed $resource_name
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User whereUsername($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelUser\User whereUpdatedAt($value)
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
    protected $presenter = UserPresenter::class;
    protected $table = 'users';
    protected $fillable = ['name', 'username', 'email', 'password'];
    protected $hidden = ['password', 'remember_token'];

    /**
     * @param string $attribute
     * @param string $key
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
        return encode_id($this->id, 'user');
    }

    /**
     * @param string $code
     * @return int
     */
    public static function getIdByCode($code)
    {
        return decode_id($code, 'user');
    }

    /**
     * @param string $code
     * @param array $columns
     * @return \Illuminate\Support\Collection|null|static
     */
    public static function findByCode($code, $columns = ['*'])
    {
        return static::find(static::getIdByCode($code), $columns);
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->username === 'admin';
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
}
