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

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

use App\Plans;
use App\ActivityStream;
use App\ActivityComments;
use App\Testers;
use App\Tables;

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

        // Get assigned plans from others
        $assignedResponses = Plans::getAllAssigned($user->id, 'created_at', 'DESC', 'dashboard');

        if (isset($assignedResponses)) {
            $results = array();
            foreach($assignedResponses->get() as $plan) {
                $results[] = get_object_vars($plan);
            }

            $plans['plans_assigned'] = $results;
        }

        // Display administrator created plans
        foreach($roles as $role) {
            if ($role->name == "administrator") {
                $adminCreated = Plans::getAllResponses($user->id, 'created_at', 'DESC', 'dashboard');
                $allAdmin     = array();

                foreach ($adminCreated->get() as $plan) {
                    $allTesters = Testers::getTestersByPlanId($plan->id);

                    foreach ($allTesters as $tester) {
                        $browserTester[$tester->id] = $tester->first_name;
                    }

                    $plan            = get_object_vars($plan);
                    $plan['testers'] = $browserTester;
                    $allAdmin[]      = $plan;
                }

                $plans['admin_created_plans'] = $allAdmin;
                break;
            }
        }

        // Get activity stream
        $activityStream = ActivityStream::getActivityStream($user->id);

        // Return view
        return view('pages.main.dashboard', [
            'activities' => $activityStream,
            'plans'      => isset($plans) ? array_filter($plans) : ''
        ]);
    }
}