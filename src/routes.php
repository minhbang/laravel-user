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
        Route::post('user/{user}/quick_update', ['as' => 'backend.user.quick_update', 'uses' => 'UserController@quickUpdate']);
        Route::get('user/of/{type}', ['as' => 'backend.user.type', 'uses' => 'UserController@index']);
        Route::resource('user', 'UserController');

        // User Group Manage
        Route::group(
            ['prefix' => 'user_group', 'as' => 'backend.user_group.'],
            function () {
                Route::get('data', ['as' => 'data', 'uses' => 'GroupController@data']);
                Route::get('{user_group}/create', 'GroupController@createChildOf');
                Route::post('move', ['as' => 'move', 'uses' => 'GroupController@move']);
                Route::post('{user_group}', ['as' => 'storeChildOf', 'uses' => 'GroupController@storeChildOf']);
            }
        );
        Route::get('user_group/of/{type}', ['as' => 'backend.user_group.type', 'uses' => 'GroupController@index']);
        Route::resource('user_group', 'GroupController');
    }
);
