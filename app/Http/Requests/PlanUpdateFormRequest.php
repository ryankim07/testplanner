<?php namespace App\Http\Requests;

/**
 * Class PlanUpdateFormRequest
 *
 * Validator and sanitizer
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

use App\Http\Requests\Request;

class PlanUpdateFormRequest extends Request
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
		$rules = [
			'description' => 'required',
			'started_at'  => 'required|date_format:"m/d/Y"',
			'expired_at'  => 'required|date_format:"m/d/Y"'
		];

		$desc = $this->request->get('desc');
		$desc = array_filter($desc);

		if (count($desc) == 0) {
			$rules['desc.0'] = 'required';
		}

		$testers = $this->request->get('tester');

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
		$messages = [
			'description.required'   => 'Description name is required',
			'started_at.required'    => 'Test start date is required',
			'started_at.date_format' => 'Test start date format must be MM/DD/YYYY',
			'expired_at.required'    => 'Test expiration date is required',
			'expired_at.date_format' => 'Test expiration date format must be MM/DD/YYYY'
		];

		$desc = $this->request->get('desc');
		$desc = array_filter($desc);

		if (count($desc) == 0) {
			$messages['desc.0.required'] = 'At least one ticket must be created';
		}

		$testers  = $this->request->get('tester');

		if (!isset($testers)) {
			$messages['tester.0.required'] = 'At least one tester must be assigned';
		}

		return $messages;
	}
}