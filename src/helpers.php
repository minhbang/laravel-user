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
if (!function_exists('user_model')) {
    /**
     * Lấy user model
     *
     * @param int|null $id
     * @return \Minhbang\LaravelUser\User|null
     */
    function user_model($id = null)
    {
        if ($id) {
            $class = config('auth.model');
            return $class::find($id);
        } else {
            return auth()->user();
        }
    }
}
if (!function_exists('user_public_path')) {
    /**
     * Thư mục 'public' của user
     * Khi có lỗi, $ignore_error =
     * - true: return [eror code, message]
     * - false: dừng app bằng 'abort'
     *
     * @param string $path
     * @param bool $full path đầy đủ
     * @param bool $ignore_error
     * @return string|array
     */
    function user_public_path($path = '', $full = false, $ignore_error = false)
    {
        if ($code = user('code')) {
            $path = '/' . setting('system.public_files') . "/$code" . ($path ? "/$path" : '');
            $path_full = public_path() . $path;
            return check_path($path_full, $ignore_error, $full ? $path_full : $path);
        } else {
            return response_error(3, 'Not call user_public_path() for Guest', $ignore_error);
        }
    }
}

if (!function_exists('user_storage_path')) {
    /**
     * Thư mục 'storage' của user
     * Khi có lỗi, $ignore_error =
     * - true: return [eror code, message]
     * - false: dừng app bằng 'abort'
     *
     * @param string $path
     * @param bool $ignore_error
     * @return string|array
     */
    function user_storage_path($path = '', $ignore_error = false)
    {
        if ($username = user('username')) {
            $path = storage_path("data/{$username}" . ($path ? "/$path" : ''));
            return check_path($path, $ignore_error, $path);
        } else {
            return response_error(3, 'Not call user_storage_path() for Guest', $ignore_error);
        }
    }
}