<?php

namespace Minhbang\User;

use Illuminate\Routing\Router;
use Illuminate\Foundation\AliasLoader;
use Minhbang\Kit\Extensions\BaseServiceProvider;
use MenuManager;

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
        $this->publishes(
            [
                __DIR__ . '/../views'           => base_path('resources/views/vendor/user'),
                __DIR__ . '/../lang'            => base_path('resources/lang/vendor/user'),
                __DIR__ . '/../config/user.php' => config_path('user.php'),
            ]
        );
        $this->publishes(
            [
                __DIR__ . '/../database/migrations/2014_10_11_000000_create_user_groups_table.php'     =>
                    database_path('migrations/2014_10_11_000000_create_user_groups_table.php'),
                __DIR__ . '/../database/migrations/2014_10_12_000000_create_users_table.php'           =>
                    database_path('migrations/2014_10_12_000000_create_users_table.php'),
                __DIR__ . '/../database/migrations/2014_10_12_100000_create_password_resets_table.php' =>
                    database_path('migrations/2014_10_12_100000_create_password_resets_table.php'),
                __DIR__ . '/../database/migrations/2014_11_05_100000_create_role_user_table.php'       =>
                    database_path('migrations/2014_11_05_100000_create_role_user_table.php'),
            ],
            'db'
        );

        $this->mapWebRoutes($router, __DIR__ . '/routes.php', config('user.add_route'));

        // pattern filters
        $router->pattern('user', '[0-9]+');
        $router->pattern('user_group', '[0-9]+');
        // model bindings
        $router->model('user', 'Minhbang\User\User');
        $router->model('user_group', 'Minhbang\User\Group');

        // Validator rule kiểm tra password hiện tại
        $this->app['validator']->extend(
            'password_check',
            function ($attribute, $value, $parameters) {
                $user = $this->app['db']->table('users')->where('id', user('id'))->first();

                return $user && $this->app['hash']->check($value, $user->password);
            }
        );

        // Add user menus
        MenuManager::addItems(config('user.menus'));
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
        $this->app->singleton('role-manager', function () {
            return new RoleManager(
                config('user.roles'),
                config('user.role_groups')
            );
        });
        // add AccessControl alias
        $this->app->booting(
            function () {
                AliasLoader::getInstance()->alias('UserManager', ManagerFacade::class);
                AliasLoader::getInstance()->alias('RoleManager', RoleManagerFacade::class);
            }
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['user-manager', 'role-manager'];
    }
}
