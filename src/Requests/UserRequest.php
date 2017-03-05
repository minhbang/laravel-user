<?php
namespace Minhbang\User\Requests;

use Minhbang\Kit\Extensions\Request;

/**
 * Class UserRequest
 *
 * @property-read \Minhbang\User\User $user
 * @package Minhbang\User\Requests
 */
class UserRequest extends Request
{
    public $trans_prefix = 'user::user';
    public $rules = [
        'username' => 'required|between:4,20|alpha_dash|unique:users',
        'name'     => 'required|min:4',
        'email'    => 'required|email|unique:users',
        'group_id' => 'required|integer|min:1|exists:user_groups,id',
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
        if ($this->user) {
            //update User
            $this->rules['username'] .= ',username,' . $this->user->id;
            $this->rules['email'] .= ',email,' . $this->user->id;
        } else {
            //create User
            $this->rules['password'] = 'required|between:4,16';
        }

        return $this->rules;
    }

}
