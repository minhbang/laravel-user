<?php
namespace Minhbang\User\Controllers\Backend;

use Minhbang\LaravelKit\Extensions\BackendController;
use  Minhbang\User\Requests\UpdatePasswordRequest;
use  Minhbang\User\Requests\UpdateProfileRequest;
use Illuminate\Contracts\Auth\Guard;
use Session;

/**
 * Class AccountController
 *
 * @package Minhbang\User\Controllers\Backend
 */
class AccountController extends BackendController
{
    /**
     * @param \Illuminate\Contracts\Auth\Guard $auth
     */
    public function __construct(Guard $auth)
    {
        parent::__construct();
        $this->auth = $auth;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getPassword()
    {
        $this->buildHeading(
            trans('user::account.update_password'),
            'fa-user-secret',
            [route('backend.user.index') => trans('user::user.user'), '#' => trans('user::account.update_password')]
        );
        return view('user::update_password');
    }

    /**
     * @param \Minhbang\User\Requests\UpdatePasswordRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postPassword(UpdatePasswordRequest $request)
    {
        $user = user();
        $user->password = $request->get('password');
        $user->save();
        $this->auth->logout();
        Session::flash(
            'message',
            [
                'type'    => 'success',
                'content' => trans('user::account.change_password_success'),
            ]
        );
        return redirect(route('auth.login'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getProfile()
    {
        $account = user();
        $this->buildHeading(
            trans('user::account.profile'),
            'fa-list-alt',
            [route('backend.user.index') => trans('user::user.user'), '#' => trans('user::account.profile')]
        );
        return view('user::profile', compact('account'));
    }

    /**
     * @param \Minhbang\User\Requests\UpdateProfileRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postProfile(UpdateProfileRequest $request)
    {
        $account = user();
        $account->fill($request->except(['password', 'username']));
        $account->save();
        Session::flash(
            'message',
            [
                'type'    => 'success',
                'content' => trans('user::account.update_profile_success'),
            ]
        );
        return redirect(route('backend.dashboard'));
    }
}
