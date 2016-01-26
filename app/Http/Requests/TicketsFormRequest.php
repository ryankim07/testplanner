<?php namespace App\Http\Requests;

/**
 * Class TicketsFormRequest
 *
 * Validator and sanitizer
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://tp.nophie.us)
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
        $desc = $this->request->get('desc');
        $desc = array_filter($desc);
        $rules = [];

        if (count($desc) == 0) {
            $rules['desc.0'] = 'required';
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
        $desc  = $this->request->get('desc');
        $desc = array_filter($desc);
        $messages = [];

        if (count($desc) == 0) {
            $messages['desc.0.required'] = 'At least one ticket must be created';
        }

        return $messages;
	}
}