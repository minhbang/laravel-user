<?php
namespace Minhbang\User\Controllers\Backend;

use Request;
use UserManager;
use Minhbang\User\Group;
use Minhbang\User\Requests\GroupRequest;
use Minhbang\Kit\Extensions\BackendController;

/**
 * Class GroupController
 *
 * @package Minhbang\User\Controllers\Backend
 */
class GroupController extends BackendController
{
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
    protected function switchType($type = null)
    {
        $this->type = $type ?: 'normal';
        session(['backend.user.group_type' => $this->type]);
    }

    /**
     * Lấy user group manager
     *
     * @return \Minhbang\User\GroupManager
     */
    protected function manager()
    {
        if (!$this->manager) {
            $this->manager = UserManager::groups(session('backend.user.group_type', 'normal'));
        }

        return $this->manager;
    }

    /**
     * @param string|null $type
     *
     * @return \Illuminate\View\View
     */
    public function index($type = null)
    {
        $this->switchType($type);
        $max_depth = $this->manager()->max_depth;
        $nestable = $this->manager()->nestable();
        $types = $this->manager()->typeNames();
        $current = $this->type;
        $this->buildHeading(
            [trans('user::group.manage'), "[{$types[$current]}]"],
            'fa-sitemap',
            ['#' => trans('user::group.group')]
        );

        return view('user::group.index', compact('max_depth', 'nestable', 'types', 'current'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return $this->_create();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Minhbang\User\Group $group
     *
     * @return \Illuminate\View\View
     */
    public function createChildOf(Group $group)
    {
        return $this->_create($group);
    }

    /**
     * @param null|\Minhbang\User\Group $parent
     *
     * @return \Illuminate\View\View
     */
    protected function _create($parent = null)
    {
        if ($parent) {
            $parent_title = $parent->full_name;
            $url = route('backend.user_group.storeChildOf', ['user_group' => $parent->id]);
        } else {
            $parent_title = '- ROOT -';
            $url = route('backend.user_group.store');
        }
        $group = new Group();
        $method = 'post';

        return view('user::group.form', compact('parent_title', 'url', 'method', 'group'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Minhbang\User\Requests\GroupRequest $request
     *
     * @return \Illuminate\View\View
     */
    public function store(GroupRequest $request)
    {
        return $this->_store($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Minhbang\User\Requests\GroupRequest $request
     * @param \Minhbang\User\Group $group
     *
     * @return \Illuminate\View\View
     */
    public function storeChildOf(GroupRequest $request, Group $group)
    {
        return $this->_store($request, $group);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Minhbang\User\Requests\GroupRequest $request
     * @param null|\Minhbang\User\Group $parent
     *
     * @return \Illuminate\View\View
     */
    public function _store($request, $parent = null)
    {
        $group = new Group();
        $group->fill($request->all());
        $group->save();
        $group->makeChildOf($parent ?: $this->manager()->typeRoot());

        return view(
            '_modal_script',
            [
                'message'    => [
                    'type'    => 'success',
                    'content' => trans('common.create_object_success', ['name' => trans('user::group.group')]),
                ],
                'reloadPage' => true,
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param \Minhbang\User\Group $group
     *
     * @return \Illuminate\View\View
     */
    public function show(Group $group)
    {
        return view('user::group.show', compact('group'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Minhbang\User\Group $group
     *
     * @return \Illuminate\View\View
     */
    public function edit(Group $group)
    {
        $parent = $group->parent;
        $parent_title = $parent->isRoot() ? '- ROOT -' : $parent->full_name;
        $url = route('backend.user_group.update', ['user_group' => $group->id]);
        $method = 'put';

        return view('user::group.form', compact('parent_title', 'url', 'method', 'group'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Minhbang\User\Requests\GroupRequest $request
     * @param \Minhbang\User\Group $group
     *
     * @return \Illuminate\View\View
     */
    public function update(GroupRequest $request, Group $group)
    {
        $group->fill($request->all());
        $group->save();

        return view(
            '_modal_script',
            [
                'message'    => [
                    'type'    => 'success',
                    'content' => trans('common.update_object_success', ['name' => trans('user::group.group')]),
                ],
                'reloadPage' => true,
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Minhbang\User\Group $group
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function destroy(Group $group)
    {
        $group->delete();

        return response()->json(
            [
                'type'    => 'success',
                'content' => trans('common.delete_object_success', ['name' => trans('user::group.group')]),
            ]
        );
    }

    /**
     * @param string $type
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function data($type)
    {
        $this->switchType($type);

        return response()->json(['html' => $this->manager()->nestable()]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function move()
    {
        if ($group = $this->getNode('element')) {
            if ($leftNode = $this->getNode('left')) {
                $group->moveToRightOf($leftNode);
            } else {
                if ($rightNode = $this->getNode('right')) {
                    $group->moveToLeftOf($rightNode);
                } else {
                    if ($destNode = $this->getNode('parent')) {
                        $group->makeChildOf($destNode);
                    } else {
                        return $this->dieAjax();
                    }
                }
            }

            return response()->json(
                [
                    'type'    => 'success',
                    'content' => trans('common.order_object_success', ['name' => trans('user::group.group')]),
                ]
            );
        } else {
            return $this->dieAjax();
        }
    }

    /**
     * @param string $name
     *
     * @return null|\Minhbang\User\Group
     */
    protected function getNode($name)
    {
        $id = Request::input($name);
        if ($id) {
            if ($node = Group::find($id)) {
                return $node;
            } else {
                return $this->dieAjax();
            }
        } else {
            return null;
        }
    }

    /**
     * Kết thúc App, trả về message dạng JSON
     *
     * @return mixed
     */
    protected function dieAjax()
    {
        return die(json_encode(
            [
                'type'    => 'error',
                'content' => trans('user::group.not_found'),
            ]
        ));
    }
}
