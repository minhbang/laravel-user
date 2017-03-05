<?php
Route::group(
    ['namespace' => 'Minhbang\User\Controllers', 'middleware' => 'web'],
    function () {
        // Auth
        Route::get('auth/logout', ['as' => 'auth.logout', 'uses' => 'AuthController@logout']);
        Route::group(['prefix' => 'auth', 'middleware' => 'guest'], function () {
            Route::get('login', ['as' => 'auth.login', 'uses' => 'AuthController@showLogin']);
            Route::post('login', ['uses' => 'AuthController@login']);
        });
        // Password
        Route::group(['prefix' => 'password', 'middleware' => 'guest'], function () {
            Route::get('email', ['as' => 'password.email', 'uses' => 'PasswordController@showEmail']);
            Route::post('email', ['uses' => 'PasswordController@email']);
            Route::get('reset', ['as' => 'password.reset', 'uses' => 'PasswordController@showReset']);
            Route::post('reset', ['uses' => 'PasswordController@reset']);
        });
        // Account
        Route::group(['prefix' => 'account', 'middleware' => 'auth'], function () {
            Route::get('password', ['as' => 'account.password', 'uses' => 'AccountController@showPassword']);
            Route::post('password', ['uses' => 'AccountController@password']);
            Route::get('profile', ['as' => 'account.profile', 'uses' => 'AccountController@showProfile']);
            Route::post('profile', ['uses' => 'AccountController@profile']);
        });
    }
);
// Backend ===================================================================================
Route::group(
    ['prefix' => 'backend', 'as' => 'backend.', 'namespace' => 'Minhbang\User\Controllers\Backend'],
    function () {
        // User Manage
        Route::group(['middleware' => config('user.middlewares.user')], function () {
            Route::group(
                ['prefix' => 'user', 'as' => 'user.'],
                function () {
                    Route::get('data/{type}', ['as' => 'data', 'uses' => 'UserController@data']);
                    Route::post('{user}/quick_update',
                        ['as' => 'quick_update', 'uses' => 'UserController@quickUpdate']);
                    Route::get('of/{type}', ['as' => 'type', 'uses' => 'UserController@index']);
                    Route::get('select/{query}/{ignore?}', ['as' => 'select', 'uses' => 'UserController@select']);
                }
            );
            Route::resource('user', 'UserController');
        });

        // User Group Manage
        Route::group(['middleware' => config('user.middlewares.group')], function () {
            Route::group(
                ['prefix' => 'user_group', 'as' => 'user_group.'],
                function () {
                    Route::get('data/{type}', ['as' => 'data', 'uses' => 'GroupController@data']);
                    Route::get('{user_group}/create', 'GroupController@createChildOf');
                    Route::post('move', ['as' => 'move', 'uses' => 'GroupController@move']);
                    Route::post('{user_group}', ['as' => 'storeChildOf', 'uses' => 'GroupController@storeChildOf']);
                    Route::get('of/{type}', ['as' => 'type', 'uses' => 'GroupController@index']);
                }
            );
            Route::resource('user_group', 'GroupController');
        });
        // Role Manage
        Route::group(
            ['prefix' => 'role', 'as' => 'role.', 'middleware' => config('user.middlewares.role')],
            function () {
                Route::get('/', ['as' => 'index', 'uses' => 'RoleController@index']);
                Route::get('{role}', ['as' => 'show', 'uses' => 'RoleController@show']);
                // Link User
                Route::group(
                    ['prefix' => '{role}/user', 'as' => 'user.'],
                    function () {
                        Route::post('{user}', ['as' => 'attach', 'uses' => 'RoleController@attachUser']);
                        Route::delete('{user}', ['as' => 'detach', 'uses' => 'RoleController@detachUser']);
                        Route::delete('/', ['as' => 'detach_all', 'uses' => 'RoleController@detachAllUser']);
                    }
                );
            }
        );
    }
);
