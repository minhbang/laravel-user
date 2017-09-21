<?php
namespace Minhbang\User\Controllers\Backend;

use Minhbang\Kit\Extensions\BackendController;
use Minhbang\Kit\Traits\Controller\QuickUpdateActions;
use Minhbang\User\User;
use UserManager;
use Request;
use DataTables;
use Minhbang\User\Requests\UserRequest;
use Minhbang\Kit\Extensions\DatatableBuilder as Builder;
use Minhbang\User\UserTransformer;

/**
 * Class UserController
 *
 * @package Minhbang\User\Controllers\Backend
 */
class UserController extends BackendController
{
    use QuickUpdateActions;

    /**
     * Quản lý user group
     *
     * @var \Minhbang\User\GroupManager
     */
    protected $manager;
    /**
     * @var string user group type hiện tại
     */
    protected $type;

    /**
     * @param null|string $type
     */
    protected function switchGroupType($type = null)
    {
        $this->type = $type ?: 'normal';
        session(['backend.user.type' => $this->type]);
    }

    /**
     * Lấy user group manager
     *
     * @return \Minhbang\User\GroupManager
     */
    protected function manager()
    {
        if (!$this->manager) {
            $this->manager = UserManager::groups(session('backend.user.type', 'normal'));
        }

        return $this->manager;
    }

    /**
     * @param \Minhbang\Kit\Extensions\DatatableBuilder $builder
     * @param string $type
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Builder $builder, $type = null)
    {
        $this->switchGroupType($type);
        $typeName = $this->manager()->typeName();
        $buttons = [];
        foreach ($this->manager()->typeNames() as $t => $n) {
            $buttons[] = [
                route('backend.user.type', ['type' => $t]),
                $n,
                ['type' => $t == $this->type ? 'info' : 'white', 'size' => 'sm'],
            ];
        }

        $this->buildHeading(
            [trans('user::user.manage') . ":", $typeName],
            'fa-users',
            ['#' => trans('user::user.user')],
            $buttons
        );

        $builder->ajax(route('backend.user.data', ['type' => $this->type]));
        $html = $builder->columns([
            ['data' => 'id', 'name' => 'id', 'title' => 'ID', 'class' => 'min-width text-center'],
            [
                'data'  => 'username',
                'name'  => 'username',
                'title' => trans('user::user.username'),
                'class' => 'min-width',
            ],
            ['data' => 'name', 'name' => 'name', 'title' => trans('user::user.name')],
            ['data' => 'email', 'name' => 'email', 'title' => trans('user::user.email')],
            [
                'data'       => 'roles',
                'name'       => 'roles',
                'title'      => trans('authority::common.roles'),
                'searchable' => false,
                'orderable'  => false,
            ],
        ])->addAction([
            'data'  => 'actions',
            'name'  => 'actions',
            'title' => trans('common.actions'),
            'class' => 'min-width',
        ]);

        return view('user::index', compact('html', 'typeName'));
    }

    /**
     * @param string $type
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function data($type)
    {
        $this->switchGroupType($type);
        /** @var User $query */
        $query = User::inGroup($this->manager()->typeRoot())->adminFirst();

        if (Request::has('filter_form')) {
            $query = $query
                ->searchWhereBetween('users.created_at', 'mb_date_vn2mysql')
                ->searchWhereBetween('users.updated_at', 'mb_date_vn2mysql');
        }

        return DataTables::of($query)->setTransformer(new UserTransformer())->make(true);
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $user = new User();
        $url = route('backend.user.store');
        $method = 'post';
        $groups = $this->manager()->selectize();
        $this->buildHeading(
            trans('common.create_object', ['name' => trans('user::user.user')]),
            'plus-sign',
            [
                route('backend.user.index') => trans('user::user.user'),
                '#'                         => trans('common.create'),
            ]
        );

        return view('user::form', compact('user', 'url', 'method', 'groups'));
    }

    /**
     * @param \Minhbang\User\Requests\UserRequest $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function store(UserRequest $request)
    {
        $user = new User();
        $user->fill($request->all());
        $user->save();

        return view(
            'kit::_modal_script',
            [
                'message'     => [
                    'type'    => 'success',
                    'content' => trans('common.create_object_success', ['name' => trans('user::user.user')]),
                ],
                'reloadTable' => 'user-manage',
            ]
        );

    }

    /**
     * @param \Minhbang\User\User $user
     *
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        return view('user::show', compact('user'));
    }

    /**
     * @param \Minhbang\User\User $user
     *
     * @return \Illuminate\View\View
     * @throws \Laracasts\Presenter\Exceptions\PresenterException
     */
    public function edit(User $user)
    {
        $this->checkUser($user);
        $url = route('backend.user.update', ['user' => $user->id]);
        $method = 'put';
        $groups = $this->manager()->selectize();
        $this->buildHeading(
            trans('common.update_object', ['name' => trans('user::user.user')]),
            'edit',
            [
                route('backend.user.index') => trans('user::user.user'),
                '#'                         => trans('common.edit'),
            ]
        );

        return view('user::form', compact('user', 'url', 'method', 'groups'));
    }

    /**
     * @param \Minhbang\User\Requests\UserRequest $request
     * @param \Minhbang\User\User $user
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(UserRequest $request, User $user)
    {
        $this->checkUser($user);
        $user->fill($request->all());
        $user->save();

        return view(
            'kit::_modal_script',
            [
                'message'     => [
                    'type'    => 'success',
                    'content' => trans('common.update_object_success', ['name' => trans('user::user.user')]),
                ],
                'reloadTable' => 'user-manage',
            ]
        );
    }

    /**
     * @param \Minhbang\User\User $user
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy(User $user)
    {
        $this->checkUser($user, true);
        $user->delete();

        return response()->json(
            [
                'type'    => 'success',
                'content' => trans('common.delete_object_success', ['name' => trans('user::user.user')]),
            ]
        );
    }

    /**
     * Lấy danh sách users sử dụng cho selectize_user
     *
     * @param string $username
     * @param null|string $ignore Những ID bỏ qua, phân cách ','
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function select($username, $ignore = null)
    {
        return response()->json(User::forSelectize($ignore)->findText('username', $username)->get()->all());
    }

    /**
     * Kiểm tra không được update thông tin của chính mình
     *
     * @param \Minhbang\User\User $user
     * @param bool $ajax
     */
    protected function checkUser($user, $ajax = false)
    {
        if (user('id') == $user->id) {
            if ($ajax) {
                die(json_encode(
                    [
                        'type'    => 'error',
                        'content' => trans('user::user.not_self_update'),
                    ]
                ));
            } else {
                abort(403, trans('user::user.not_self_update'));
            }
        }
    }

    /**
     * Các attributes cho phéo quick-update
     *
     * @return array
     */
    protected function quickUpdateAttributes()
    {
        return [
            'username' => [
                'rules' => 'required|min:4|max:20|alpha_dash|unique:users,username,__ID__',
                'label' => trans('user::user.username'),
            ],
            'name'     => ['rules' => 'required|min:4', 'label' => trans('user::user.name')],
            'email'    => ['rules' => 'required|email|unique:users,email,__ID__', 'label' => trans('user::user.email')],
        ];
    }

    /**
     * Không cho quick update với admin và new username của user khác không được = 'admin'
     *
     * @param \Minhbang\User\User $user
     * @param string $attribute
     * @param string $value
     *
     * @return bool
     */
    protected function quickUpdateAllowed($user, $attribute, $value)
    {
        return ($user->username != 'admin') && ($attribute != 'username' || $value != 'admin');
    }
}
