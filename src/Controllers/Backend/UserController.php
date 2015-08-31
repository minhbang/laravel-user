<?php
namespace Minhbang\LaravelUser\Controllers\Backend;

use Minhbang\LaravelKit\Extensions\BackendController;
use Minhbang\LaravelUser\User;
use Request;
use Datatable;
use Html;
use Minhbang\LaravelUser\Requests\UserRequest;

/**
 * Class UserController
 *
 * @package Minhbang\LaravelUser\Controllers\Backend
 */
class UserController extends BackendController
{
    public function __construct()
    {
        parent::__construct(config('user.middlewares.user'));
    }

    /**
     * Danh sách User theo định dạng của Datatables.
     *
     * @return \Datatable JSON
     */
    public function data()
    {
        /** @var User $query */
        $query = User::orderUpdated();
        if (Request::has('search_form')) {
            $query = $query
                ->searchWhereBetween('users.created_at', 'mb_date_vn2mysql')
                ->searchWhereBetween('users.updated_at', 'mb_date_vn2mysql');
        }
        return Datatable::query($query)
            ->addColumn(
                'index',
                function (User $model) {
                    return $model->id;
                }
            )
            ->addColumn(
                'username',
                function (User $model) {
                    return $model->username;
                }
            )
            ->addColumn(
                'name',
                function (User $model) {
                    return $model->name;
                }
            )
            ->addColumn(
                'email',
                function (User $model) {
                    return $model->email;
                }
            )
            ->addColumn(
                'actions',
                function (User $model) {
                    return user('id') == $model->id ? '' : Html::tableActions(
                        'backend/user',
                        $model->id,
                        "{$model->name} ({$model->username})",
                        trans('user::user.user'),
                        [
                            //'renderEdit' => 'link',
                            //'renderShow' => 'modal-large',
                        ]
                    );
                }
            )
            ->searchColumns('users.username', 'users.name')
            ->make();
    }

    /**
     * @return \Illuminate\View\View
     * @throws \Exception
     * @throws \Laracasts\Presenter\Exceptions\PresenterException
     */
    public function index()
    {
        $tableOptions = [
            'id'        => 'user-manage',
            'row_index' => true,
        ];
        $options = [
            'aoColumnDefs' => [
                ['sClass' => 'min-width', 'aTargets' => [0, 1, -1]],
            ],
        ];
        $table = Datatable::table()
            ->addColumn(
                '#',
                trans('user::user.username'),
                trans('user::user.name'),
                trans('user::user.email'),
                trans('common.actions')
            )
            ->setOptions($options)
            ->setCustomValues($tableOptions);
        $this->buildHeading(trans('user::user.manage'), 'fa-users', ['#' => trans('user::user.user')]);
        return view('user::index', compact('tableOptions', 'options', 'table'));
    }

    /**
     * @return \Illuminate\View\View
     * @throws \Laracasts\Presenter\Exceptions\PresenterException
     */
    public function create()
    {
        $user = new User();
        $url = route('backend.user.store');
        $method = 'post';
        $this->buildHeading(
            trans('common.create_object', ['name' => trans('user::user.user')]),
            'plus-sign',
            [
                route('backend.user.index') => trans('user::user.user'),
                '#'                         => trans('common.create'),
            ]
        );
        return view('user::form', compact('user', 'url', 'method'));
    }

    /**
     * @param \Minhbang\LaravelUser\Requests\UserRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(UserRequest $request)
    {
        $user = new User();
        $user->fill($request->all());
        $user->save();
        return view(
            '_modal_script',
            [
                'message'     => [
                    'type'    => 'success',
                    'content' => trans('common.create_object_success', ['name' => trans('user::user.user')])
                ],
                'reloadTable' => 'user-manage',
            ]
        );

    }

    /**
     * @param \Minhbang\LaravelUser\User $user
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        return view('user::show', compact('user'));
    }

    /**
     * @param \Minhbang\LaravelUser\User $user
     * @return \Illuminate\View\View
     * @throws \Laracasts\Presenter\Exceptions\PresenterException
     */
    public function edit(User $user)
    {
        $this->checkUser($user);
        $url = route('backend.user.update', ['user' => $user->id]);
        $method = 'put';
        $this->buildHeading(
            trans('common.update_object', ['name' => trans('user::user.user')]),
            'edit',
            [
                route('backend.user.index') => trans('user::user.user'),
                '#'                         => trans('common.edit'),
            ]
        );
        return view('user::form', compact('user', 'url', 'method'));
    }

    /**
     * @param \Minhbang\LaravelUser\Requests\UserRequest $request
     * @param \Minhbang\LaravelUser\User $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(UserRequest $request, User $user)
    {
        $this->checkUser($user);
        $user->fill($request->all());
        $user->save();
        return view(
            '_modal_script',
            [
                'message'     => [
                    'type'    => 'success',
                    'content' => trans('common.update_object_success', ['name' => trans('user::user.user')])
                ],
                'reloadTable' => 'user-manage',
            ]
        );
    }

    /**
     * @param \Minhbang\LaravelUser\User $user
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
     * Kiểm tra không được update thông tin của chính mình
     *
     * @param \Minhbang\LaravelUser\User $user
     * @param bool $ajax
     */
    protected function checkUser($user, $ajax = false)
    {
        if (user('id') == $user->id) {
            if ($ajax) {
                die(json_encode(
                    [
                        'type'    => 'error',
                        'content' => trans('user::user.not_self_update')
                    ]
                ));
            } else {
                abort(403, trans('user::user.not_self_update'));
            }
        }
    }
}
