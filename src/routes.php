<?php
Route::group(
    ['namespace' => 'Minhbang\User\Controllers'],
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
    ['prefix' => 'backend', 'namespace' => 'Minhbang\User\Controllers\Backend'],
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
        Route::group(
            ['prefix' => 'user', 'as' => 'backend.user.'],
            function () {
                Route::get('data', ['as' => 'data', 'uses' => 'UserController@data']);
                Route::post('{user}/quick_update', ['as' => 'quick_update', 'uses' => 'UserController@quickUpdate']);
                Route::get('of/{type}', ['as' => 'type', 'uses' => 'UserController@index']);
                Route::get('select/{query}/{ignore?}', ['as' => 'select', 'uses' => 'UserController@select']);
            }
        );
        Route::resource('user', 'UserController');

        // User Group Manage
        Route::group(
            ['prefix' => 'user_group', 'as' => 'backend.user_group.'],
            function () {
                Route::get('data', ['as' => 'data', 'uses' => 'GroupController@data']);
                Route::get('{user_group}/create', 'GroupController@createChildOf');
                Route::post('move', ['as' => 'move', 'uses' => 'GroupController@move']);
                Route::post('{user_group}', ['as' => 'storeChildOf', 'uses' => 'GroupController@storeChildOf']);
                Route::get('of/{type}', ['as' => 'type', 'uses' => 'GroupController@index']);
            }
        );
        Route::resource('user_group', 'GroupController');
    }
);
