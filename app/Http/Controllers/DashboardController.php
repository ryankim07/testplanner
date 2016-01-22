<?php namespace App\Http\Controllers;

/**
 * Class DashboardController
 *
 * Dashboard Controller
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

use App\Http\Requests;

use App\Plans;
use App\ActivityStream;

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
     * Show all the plans assigned to user
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        // Get user's role
        $user  = Auth::user();
        $roles = $user->role()->get();

        $plans = Plans::getDashboardCreatedAssigned($user, $roles);

        // Get activity stream
        $activityStream = ActivityStream::getActivityStream();

        // Return view
        return view('pages.main.dashboard', [
            'activities'      => $activityStream['total_count'] > 0 ? $activityStream['query'] : '',
            'totalActivities' => isset($activityStream['total_count']) ? $activityStream['total_count'] : 0,
            'plans'           => isset($plans) ? array_filter($plans) : '',
            'link'            => '',
        ]);
    }
}