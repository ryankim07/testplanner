<?php namespace App\Http\Requests;

/**
 * Class PlansFormRequest
 *
 * Validator and sanitizer
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

use App\Http\Requests\Request;

class PlansFormRequest extends Request
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
			'description' => 'required',
			'started_at'  => 'required|date_format:"m/d/Y"',
			'expired_at'  => 'required|date_format:"m/d/Y"'
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
			'description.required'   => 'Description name is required',
			'started_at.required'    => 'Test start date is required',
			'started_at.date_format' => 'Test start date format must be MM/DD/YYYY',
			'expired_at.required'    => 'Test expiration date is required',
			'expired_at.date_format' => 'Test expiration date format must be MM/DD/YYYY',
		];
	}
}