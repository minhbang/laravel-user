<?php
namespace Minhbang\LaravelUser;

use Minhbang\LaravelKit\Traits\Presenter\DatetimePresenter;
use Laracasts\Presenter\Presenter;

class UserPresenter extends Presenter
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