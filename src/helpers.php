<?php
if (!function_exists('user')) {
    /**
     * Lấy user đã đăng nhập, hoặc chỉ $attribute
     *
     * @param string|null $attribute
     * @return \Minhbang\LaravelUser\User|mixed|null
     */
    function user($attribute = null)
    {
        if ($user = auth()->user()) {
            return $attribute ? $user->$attribute : $user;
        } else {
            return null;
        }
    }
}

if (!function_exists('user_path')) {
    /**
     * Thư mục 'public' của user
     *
     * @param string $path
     * @param bool $public
     * @param bool $abs
     * @return int|string
     */
    function user_path($path = '', $public = true, $abs = false)
    {
        if (!($user = user())) {
            abort(500, 'Call user_path() for Guest');
        }
        $path = $path ? "/$path" : '';
        if ($public) {
            $path = '/' . setting('system.public_files') . "/$user->code.$path";
            $abs_path = public_path() . $path;
        } else {
            $abs_path = $path = storage_path("data/$user->username.$path");
        }
        if (!is_dir($abs_path) && !mkdir($abs_path, 0777, true)) {
            return 1;
        }
        if (!is_writable($abs_path) && !chmod($abs_path, 0777)) {
            return 2;
        }

        return $abs ? $abs_path : $path;
    }
}
