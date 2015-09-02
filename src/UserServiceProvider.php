<?php

namespace Minhbang\LaravelUser;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

/**
 * Class UserServiceProvider
 *
 * @package Minhbang\LaravelUser
 */
class UserServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'user');
        $this->loadViewsFrom(__DIR__ . '/../views', 'user');
        $this->publishes(
            [
                __DIR__ . '/../views'                                => base_path('resources/views/vendor/user'),
                __DIR__ . '/../lang'                                 => base_path('resources/lang/vendor/user'),
                __DIR__ . '/../config/user.php'                      => config_path('user.php'),
                __DIR__ . '/../database/migrations/' .
                '2014_10_12_000000_create_users_table.php'           =>
                    database_path('migrations/' . '2014_10_12_000000_create_users_table.php'),
                __DIR__ . '/../database/migrations/' .
                '2014_10_12_100000_create_password_resets_table.php' =>
                    database_path('migrations/' . '2014_10_12_100000_create_password_resets_table.php'),
            ]
        );

        if (config('user.add_route') && !$this->app->routesAreCached()) {
            require __DIR__ . '/routes.php';
        }
        // pattern filters
        $router->pattern('user', '[0-9]+');
        // model bindings
        $router->model('user', 'Minhbang\LaravelUser\User');

        // Validator rule kiểm tra password hiện tại
        $this->app['validator']->extend(
            'password_check',
            function ($attribute, $value, $parameters) {
                //TODO: thay bằng user() helper
                $user = $this->app['db']->table('users')->where('id', user('id'))->first();
                if ($user && $this->app['hash']->check($value, $user->password)) {
                    return true;
                } else {
                    return false;
                }
            }
        );
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/user.php', 'user');
    }
}