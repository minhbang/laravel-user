<?php
namespace Minhbang\LaravelUser\Requests;

use Minhbang\LaravelKit\Extensions\Request;

class UserRequest extends Request
{
    public $trans_prefix = 'user::user';
    public $rules = [
        'username' => 'required|min:4|max:20|alpha_dash|unique:users',
        'name'     => 'required|min:4',
        'email'    => 'required|email|unique:users',
        'password' => 'between:4,16',
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
        /** @var \Minhbang\LaravelUser\User $user */
        if ($user = $this->route('user')) {
            //update User
            $this->rules['username'] .= ',username,' . $user->id;
            $this->rules['email'] .= ',email,' . $user->id;
        } else {
            //create User
            $this->rules['password'] .= '|required';
        }
        return $this->rules;
    }

}
