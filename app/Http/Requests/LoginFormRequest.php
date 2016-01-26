<?php namespace App\Http\Requests;

/**
 * Class LoginFormRequest
 *
 * Validator
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://tp.nophie.us)
 */

use App\Http\Requests\Request;

class LoginFormRequest extends Request {

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
            'email'    => 'required',
            'password' => 'required'
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
            'email.required'    => 'Email is required',
            'email.email'       => 'Enter correct email address',
            'password.required' => 'Password is required'
        ];
    }
}