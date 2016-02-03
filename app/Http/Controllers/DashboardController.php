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

use Illuminate\Http\Request;

use App\Api\PlansApi,
    App\Api\ActivityStreamApi;

use Auth;
use Session;

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
        $user = Session::get('tp.user');
        // Get session to launch modal
        $responses = $plansApi->getPlansWithResponsesById($user['id']);

        // Get created and assigned plans
        $plans = $plansApi->getDashboardLists();

        // Get activity stream
        $activities = $activityApi->getActivityStream();

        // Return view
        return view('pages.main.dashboard', compact('responses', 'plans', 'activities'));
    }
}