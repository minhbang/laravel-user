<?php

namespace Minhbang\User;

use Laracasts\Presenter\Presenter;

/**
 * Class GroupPresenter
 *
 * @package Minhbang\User
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
        $name = __('User group');

        if ($this->entity->depth < $max_depth) {
            $child = '<a href="' . url("backend/user_group/{$this->entity->id}/create") . '"
               class="modal-link btn btn-primary btn-xs"
               data-toggle="tooltip"
               data-title="' . __('Create child :name', ['name' => $name]) . '"
               data-label="' . __('Save') . '"
               data-width="large"
               data-icon="align-justify"><span class="glyphicon glyphicon-plus"></span>
            </a>';
        } else {
            $child = '<a href="#"
               class="btn btn-primary btn-xs disabled"
               data-toggle="tooltip"
               data-title="' . __('Create child :name', ['name' => $name]) . '">
                <span class="glyphicon glyphicon-plus"></span>
            </a>';
        }
        $show = '<a href="' . url("backend/user_group/{$this->entity->id}") . '"
           data-toggle="tooltip"
           class="modal-link btn btn-success btn-xs"
           data-title="' . __('Details of :name', ['name' => $name]) . '"
           data-icon="align-justify"><span class="glyphicon glyphicon-list"></span>
        </a>';
        $edit = '<a href="' . url("backend/user_group/{$this->entity->id}/edit") . '"
           data-toggle="tooltip"
           class="modal-link btn btn-info btn-xs"
           data-title="' . __('Update :name', ['name' => $name]) . '"
           data-label="' . __('Save Shanges') . '"
           data-width="large"
           data-icon="align-justify"><span class="glyphicon glyphicon-edit"></span>
        </a>';
        $delete = '<a href="#"
            data-toggle="tooltip"
            data-title="' . __('Delete :name', ['name' => $name]) . '"
            data-item_id="' . $this->entity->id . '"
            data-item_title="' . $this->entity->full_name . '"
            class="delete_item btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span>
        </a>';

        return $child . $show . $edit . $delete;
    }
}