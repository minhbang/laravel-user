<?php
namespace Minhbang\LaravelUser\Requests;

use Minhbang\LaravelKit\Extensions\Request;

/**
 * Class GroupRequest
 *
 * @package Minhbang\LaravelUser
 */
class GroupRequest extends Request
{
    public $trans_prefix = 'user::group';
    public $rules = [
        'system_name'  => 'required|max:128|alpha_dash|unique:user_groups',
        'full_name'    => 'required|max:128',
        'short_name'   => 'required|max:60',
        'acronym_name' => 'required|max:20',
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        /** @var \Minhbang\LaravelUser\Group $group */
        if ($group = $this->route('user_group')) {
            //update Group
            $this->rules['system_name'] .= ',system_name,' . $group->id;
        } else {
            //create Group
        }
        return $this->rules;
    }

}
