<?php
namespace Minhbang\User\Controllers\Backend;

use Minhbang\Kit\Extensions\BackendController;
use Minhbang\User\User;

/**
 * Class RoleController
 *
 * @package Minhbang\User\Controllers\Backend
 */
class RoleController extends BackendController
{
    /**
     * @var \Minhbang\User\RoleManager;
     */
    protected $manager;

    public function __construct()
    {
        parent::__construct();
        $this->manager = app('role-manager');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->buildHeading(
            trans('user::role.manage'),
            'fa-male',
            ['#' => trans('user::role.roles')]
        );
        $roles = $this->manager->countedRoles();
        return view('user::role.index', compact('roles'));
    }

    /**
     * @param string $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        if (is_null($role = $this->manager->get($id))) {
            abort(404, trans('user::role.invalid'));
        }

        $this->buildHeading(
            [trans('user::role.manage') . ':', $role->full_title],
            'fa-male',
            [route('backend.role.index') => trans('user::role.roles'), '#' => $role->full_title]
        );
        // Tất cả users đã được gán role này
        $users = $role->users();
        // 10 users khác chưa gán $role
        $selectize_users = User::forSelectize($users->pluck('id'), 10)->get()->all();

        return view('user::role.show', compact('role', 'users', 'selectize_users'));
    }

    // User Manage ----------------------------------------------------
    /**
     * @param string $id
     * @param \Minhbang\User\User $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function attachUser($id, User $user)
    {
        if (is_null($role = $this->manager->get($id))) {
            abort(404, trans('user::role.invalid'));
        }
        $role->attachUser($user->id);

        return response()->json(
            [
                'type'    => 'success',
                'content' => trans('user::role.attach_user_success'),
            ]
        );
    }

    /**
     * @param string $id
     * @param \Minhbang\User\User $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detachUser($id, User $user)
    {
        if (is_null($role = $this->manager->get($id))) {
            abort(404, trans('user::role.invalid'));
        }
        $role->detachUser($user->id);

        return response()->json(
            [
                'type'    => 'success',
                'content' => trans('user::role.detach_user_success'),
            ]
        );
    }

    /**
     * @param string $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detachAllUser($id)
    {
        if (is_null($role = $this->manager->get($id))) {
            abort(404, trans('user::role.invalid'));
        }
        $role->detachUser();

        return response()->json(
            [
                'type'    => 'success',
                'content' => trans('user::role.detach_all_user_success'),
            ]
        );
    }
    // Permission Manage ----------------------------------------------
}
