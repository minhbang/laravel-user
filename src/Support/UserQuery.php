<?php
namespace Minhbang\User\Support;

/**
 * Class UserQuery
 *
 * @package Minhbang\Kit\Traits\Model
 * @property-read string $table
 * @method \Illuminate\Database\Eloquent\Relations\BelongsTo belongsTo($related, $foreignKey = null, $otherKey = null, $relation = null)
 * @mixin \Eloquent;
 */
trait UserQuery
{
    /**
     * @param string $attribute
     *
     * @return \Minhbang\User\User|mixed
     */
    public function author($attribute = null)
    {
        if ($author = $this->user) {
            return $attribute ? $author->{$attribute} : $author;
        } else {
            return null;
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeNotMine($query)
    {
        return $query->where("{$this->table}.user_id", '<>', user('id'));
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeMine($query)
    {
        return $query->where("{$this->table}.user_id", '=', user('id'));
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @param string $attribute
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeWithAuthor($query, $attribute = 'username')
    {
        // TODO dùng user() relations
        return $query->leftJoin('users', 'users.id', '=', "{$this->table}.user_id")
            ->addSelect("users.{$attribute} as author");
    }
}