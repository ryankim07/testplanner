<?php namespace App\Http\Requests;

/**
 * Class UserResponseFormRequest
 *
 * Validator and sanitizer
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://tp.nophie.us)
 */

use App\Http\Requests\Request;

class UserResponseFormRequest extends Request
{
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
        return [
			'role'       			=> 'required',
			'first_name' 			=> 'required',
			'last_name'  			=> 'required',
			'email'      			=> 'required|email',
			'password'   			=> 'required|confirmed|min:6',
			'password_confirmation' => 'required'
        ];
    }

    /**
     * Custom error messages
     *
     * @return array
     */
    public function messages()
    {
        return [
			'role.required'       => 'Role is required',
			'first_name.required' => 'First name is required',
			'last_name.required'  => 'Last name is required',
			'email.required'      => 'Email is required',
			'email.email'         => 'Enter correct email address',
			'password.required'   => 'Password is required',
			'password.confirmed'  => 'Password confirmation do not match',
			'password.min'        => 'Password must have a length of 6 characters'
		];
	}
}