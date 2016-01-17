<?php
namespace Minhbang\User;

use Minhbang\LaravelKit\Traits\Presenter\DatetimePresenter;
use Laracasts\Presenter\Presenter as BasePresenter;

/**
 * Class Presenter
 *
 * @package Minhbang\User
 */
class Presenter extends BasePresenter
{
    use DatetimePresenter;

    /**
     * @return string
     */
    public function roles()
    {
        $names = [];
        foreach($this->entity->roles as $role){
            $names[] = "<code>{$role->full_name}</code>";
        }
        return implode('<br />', $names);
    }
}