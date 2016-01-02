<?php namespace App\Http\Requests;

/**
 * Class TicketsFormRequest
 *
 * Validator and sanitizer
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

use App\Http\Requests\Request;

class TicketsFormRequest extends Request
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
        $description = $this->request->get('description');
        $description = array_filter($description);
        $rules = [];

        if (count($description) == 0) {
            $rules['description.0'] = 'required';
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
        $description  = $this->request->get('description');
        $description = array_filter($description);
        $messages = [];

        if (count($description) == 0) {
            $messages['description.0.required'] = 'At least one ticket must be created';
        }

        return $messages;
	}
}