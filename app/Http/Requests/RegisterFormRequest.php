<?php namespace App\Http\Requests;

/**
 * Class RegisterFormRequest
 *
 * Validator
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2015 mophie (https://lpp.nophie.com)
 */

use App\Http\Requests\Request;

class RegisterFormRequest extends Request {

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
            'email'    => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
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
            'email.required'     => 'Email is required',
            'email.email'        => 'Enter correct email address',
            'email.unique'       => 'An account already exists with this associated email',
            'password.required'  => 'Password is required',
            'password.confirmed' => 'Password confirmation is required',
            'password.min'       => 'Password must have a length of 6 characters'
        ];
    }
}