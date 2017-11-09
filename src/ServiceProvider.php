<?php

namespace Minhbang\User;

use Illuminate\Routing\Router;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Authority;
use Kit;

/**
 * Class ServiceProvider
 *
 * @package Minhbang\User
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'user');
        $this->loadViewsFrom(__DIR__ . '/../views', 'user');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes(
            [
                __DIR__ . '/../views'           => base_path('resources/views/vendor/user'),
                __DIR__ . '/../lang'            => base_path('resources/lang/vendor/user'),
                __DIR__ . '/../config/user.php' => config_path('user.php'),
            ]
        );

        // pattern filters
        $router->pattern('user', '[0-9]+');
        $router->pattern('user_group', '[0-9]+');
        // model bindings
        $router->model('user', 'Minhbang\User\User');
        $router->model('user_group', 'Minhbang\User\Group');
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        // Validator rule kiểm tra password hiện tại
        $this->app['validator']->extend(
            'password_check',
            function ($attribute, $value, $parameters) {
                $user = $this->app['db']->table('users')->where('id', user('id'))->first();

                return $user && $this->app['hash']->check($value, $user->password);
            }
        );

        if(app()->getAlias('menu-manager')){
            app('menu-manager')->addItems(config('user.menus'));
        }
        Authority::permission()->registerCRUD(User::class);
        Kit::title(User::class, trans('user::user.users'));
    }


    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/user.php', 'user');
        $this->app->singleton('user-manager', function () {
            return new Manager(
                config('user.group_types'),
                config('user.group_max_depth')
            );
        });
        // add AccessControl alias
        $this->app->booting(function () {
            AliasLoader::getInstance()->alias('UserManager', Facade::class);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['user-manager'];
    }
}
