<?php namespace App\Http\Controllers;

/**
 * Class DashboardController
 *
 * Dashboard Controller
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://tp.nophie.us)
 */

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Api\PlansApi,
    App\Api\ActivityStreamApi;

use Auth;

class DashboardController extends Controller
{
    /**
     * DashboardController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('testplanner', ['only' => ['index']]);
    }

    /**
     * Show dashboard lists and activity stream
     *
     * @param PlansApi $plansApi
     * @param ActivityStreamApi $activityApi
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index(PlansApi $plansApi, ActivityStreamApi $activityApi)
    {
        // Get created and assigned plans
        $plans = $plansApi->getDashboardLists();

        // Get activity stream
        $activities = $activityApi->getActivityStream();

        // Return view
        return view('pages.main.dashboard', compact('plans', 'activities'));
    }
}