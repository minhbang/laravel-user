<?php
use Minhbang\User\User;

if (!function_exists('user')) {
    /**
     * Lấy thuộc tính của user hiện tại
     *
     * @param string $attribute
     * @param mixed $guest Giá trị trả về khi user chưa đăng nhập
     *
     * @return \Minhbang\User\User|mixed
     *
     */
    function user($attribute = null, $guest = null)
    {
        return auth()->check() ? ($attribute ? auth()->user()->{$attribute} : auth()->user()) : $guest;
    }
}

if (!function_exists('user_model')) {
    /**
     * @param null|int|\Minhbang\User\User $param
     *
     * @param bool $abortIfError
     *
     * @return \Minhbang\User\User
     */
    function user_model($param = null, $abortIfError = true)
    {
        $model = is_null($param) ? auth()->user() : ($param instanceof User ? $param : User::find($param));
        abort_if($abortIfError && is_null($model), 500, "User model is NULL");

        return $model;
    }
}