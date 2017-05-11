<?php namespace Minhbang\User;

use Minhbang\Kit\Extensions\ModelTransformer;
use Html;
use Authority;

/**
 * Class UserTransformer
 *
 * @package Minhbang\User
 */
class UserTransformer extends ModelTransformer
{
    /**
     * @param \Minhbang\User\User $user
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id'       => (int)$user->id,
            'username' => $this->quickUpdate($user, 'username'),
            'name'     => $this->quickUpdate($user, 'name'),
            'email'    => $this->quickUpdate($user, 'email', ['placement' => 'left']),
            'roles'    => $user->present()->roles,
            'actions'  => user('id') == $user->id ? '' : Html::tableActions(
                'backend.user',
                ['user' => $user->id],
                "{$user->name} ({$user->username})",
                trans('user::user.user'),
                [
                    //'renderEdit' => 'link',
                    //'renderShow' => 'modal-large',
                ]
            ),
        ];
    }

    /**
     * @param \Minhbang\User\User $user
     * @param string $attribute
     * @param array $options
     *
     * @return string
     */
    protected function quickUpdate(User $user, $attribute, $options = [])
    {
        $default = [
            'attr'  => $attribute,
            'title' => trans("user::user.{$attribute}"),
            'class' => 'w-md',
        ];
        $color = mb_array_extract('color', $options, 'danger');

        return Authority::user($user)->isSuperAdmin() ?
            '<span class="text-' . $color . '">' . $user->{$attribute} . '</span>' :
            Html::linkQuickUpdate($user->id, $user->{$attribute}, array_merge($default, $options));
    }
}