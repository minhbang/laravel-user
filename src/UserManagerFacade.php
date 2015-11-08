<?php
namespace Minhbang\LaravelUser;

use Illuminate\Support\Facades\Facade;

class UserManagerFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'user-manager';
    }
}