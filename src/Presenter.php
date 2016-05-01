<?php
namespace Minhbang\User;

use Minhbang\Kit\Traits\Presenter\DatetimePresenter;
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
        foreach ($this->entity->roles() as $role) {
            if ($role) {
                $names[] = '<code>' . \RoleManager::roles("{$role}.title") . '</code>';
            }
        }

        return implode('<br />', $names);
    }
}