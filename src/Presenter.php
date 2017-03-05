<?php
namespace Minhbang\User;

use Minhbang\Kit\Traits\Presenter\DatetimePresenter;
use Laracasts\Presenter\Presenter as BasePresenter;
use Authority;

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
        return '<code>' . implode('</code><code>', Authority::user($this->entity)->roleTitles()) . '</code>';
    }
}