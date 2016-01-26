<?php namespace App\Http\Requests;

/**
 * Class TesterFormRequest
 *
 * Validator and sanitizer
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://tp.nophie.us)
 */

use App\Http\Requests\Request;

class TestersFormRequest extends Request
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
        $testers = $this->request->get('tester');
        $rules   = [];

        if (!isset($testers)) {
            $rules['tester.0'] = 'required';
        }

        return $rules;
    }

    /**
     * Custom error messages
     *
     * @return array
     */
    public function messages()
    {
        $testers  = $this->request->get('tester');
        $messages = [];

        if (!isset($testers)) {
            $messages['tester.0.required'] = 'At least one tester must be assigned';
        }

        return $messages;
	}
}