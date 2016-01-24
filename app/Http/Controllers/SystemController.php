<?php namespace App\Http\Controllers;

/**
 * Class SystemController
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Api\SystemApi;

class SystemController extends Controller
{
    /**
     * @var SystemApi
     */
    protected $systemApi;

    /**
     * SystemController constructor
     *
     * @param SystemApi $system
     */
    public function __construct(SystemApi $system)
    {
        $this->middleware('auth');
        $this->systemApi = $system;
    }

    /**
     * Display a listing of the resource
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        // Get header and body data
        $configs = $this->systemApi->getConfigs();

        return view('pages.main.system', ['configData' => $configs]);
    }

    /**
     * Update the specified resource in storage
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $postData = array_except($request->all(), '_token');

        if (count($postData) == 0) {
            return response()->json([
                'status' => 'error',
                'msg'    => config('testplanner.messages.system.update_error')
            ]);
        }

        $update = $this->systemApi->updateConfigs($postData);

        return response()->json([
            'status' => $update ? 'success' : 'error',
            'msg'    => $update ? config('testplanner.messages.system.update_success') :
                config('testplanner.messages.system.file_update_error')
        ]);
    }
}