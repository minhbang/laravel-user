<?php
namespace Minhbang\LaravelUser;

use Laracasts\Presenter\Presenter;

/**
 * Class GroupPresenter
 *
 * @package Minhbang\LaravelUser
 */
class GroupPresenter extends Presenter
{
    /**
     * @return string
     */
    public function label()
    {
        return $this->entity->full_name;
    }

    /**
     * @param int $max_depth
     *
     * @return string
     */
    public function actions($max_depth)
    {
        $name = trans('user::group.group');

        if ($this->entity->depth < $max_depth) {
            $child = '<a href="' . url("backend/user_group/{$this->entity->id}/create") . '"
               class="modal-link btn btn-primary btn-xs"
               data-toggle="tooltip"
               data-title="' . trans('common.create_child_object', ['name' => $name]) . '"
               data-label="' . trans('common.save') . '"
               data-icon="align-justify"><span class="glyphicon glyphicon-plus"></span>
            </a>';
        } else {
            $child = '<a href="#"
               class="btn btn-primary btn-xs disabled"
               data-toggle="tooltip"
               data-title="' . trans('common.create_child_object', ['name' => $name]) . '">
                <span class="glyphicon glyphicon-plus"></span>
            </a>';
        }

        $users = '<a href="' . url("backend/user_group/{$this->entity->id}/users") . '"
           data-toggle="tooltip"
           class="modal-link btn btn-success btn-xs"
           data-title="' . trans('common.object_details_view', ['name' => $name]) . '"
           data-icon="align-justify"><span class="glyphicon glyphicon-list"></span>
        </a>';

        $show = '<a href="' . url("backend/user_group/{$this->entity->id}") . '"
           data-toggle="tooltip"
           class="modal-link btn btn-success btn-xs"
           data-title="' . trans('common.object_details_view', ['name' => $name]) . '"
           data-icon="align-justify"><span class="glyphicon glyphicon-list"></span>
        </a>';
        $edit = '<a href="' . url("backend/user_group/{$this->entity->id}/edit") . '"
           data-toggle="tooltip"
           class="modal-link btn btn-info btn-xs"
           data-title="' . trans('common.update_object', ['name' => $name]) . '"
           data-label="' . trans('common.save_changes') . '"
           data-icon="align-justify"><span class="glyphicon glyphicon-edit"></span>
        </a>';
        $delete = '<a href="#"
            data-toggle="tooltip"
            data-title="' . trans('common.delete_object', ['name' => $name]) . '"
            data-item_id="' . $this->entity->id . '"
            data-item_title="' . $this->entity->full_name . '"
            class="delete_item btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span>
        </a>';
        return $child . $show . $edit . $delete;
    }
}