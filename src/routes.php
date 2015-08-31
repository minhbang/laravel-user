<?php
Route::group(
    ['namespace' => 'Minhbang\LaravelUser\Controllers'],
    function () {
        Route::controller(
            'auth',
            'AuthController',
            [
                'getLogin'  => 'auth.login',
                'getLogout' => 'auth.logout',
            ]
        );

        Route::controller(
            'password',
            'PasswordController',
            [
                'getEmail' => 'password.email',
                'getReset' => 'password.reset',
            ]
        );
    }
);
// Backend ===================================================================================
Route::group(
    ['prefix' => 'backend', 'namespace' => 'Minhbang\LaravelUser\Controllers\Backend'],
    function () {
        Route::controller(
            'account',
            'AccountController',
            [
                'getPassword' => 'backend.account.password',
                'getProfile'  => 'backend.account.profile',
            ]
        );
        // User Manage
        Route::get('user/data', ['as' => 'backend.user.data', 'uses' => 'UserController@data']);
        Route::resource('user', 'UserController');
    }
);