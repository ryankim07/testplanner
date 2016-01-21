<?php namespace App\Http\Controllers;

/**
 * Class SystemController
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

use App\System;

class SystemController extends Controller
{
    /**
     * SystemController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        // Get header and body data
        $configs = System::getConfigs();

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
                'msg'    => config('testplanner.messages.system.update_error'),
            ]);
        }
        $results = System::updateConfig($postData);

        return response()->json([
            'status' => $results['status'],
            'msg'    => $results['msg'],
        ]);
    }
}