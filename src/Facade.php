<?php
namespace Minhbang\User;

/**
 * Class Facade
 *
 * @package Minhbang\User
 */
class Facade extends \Illuminate\Support\Facades\Facade
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