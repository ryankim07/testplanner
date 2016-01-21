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

use App\Facades\Tools;

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
        $plans = [];

        // Get assigned plans from others
        $plans['plans_assigned'] = Plans::getAllAssigned($user->id, 'created_at', 'DESC', 'dashboard')->get();

        // Display administrator created plans
        $browserTester = [];
        $allAdmin = [];
        foreach($roles as $role) {
            if ($role->name == "administrator") {
                $adminCreatedPlans = Plans::getAllResponses($user->id, 'created_at', 'DESC', 'dashboard');

                foreach ($adminCreatedPlans->get() as $adminPlan) {
                    $testers     = Plans::getTestersByPlanId($adminPlan['id']);
                    $optionsHtml = Tools::dropDownOptionsHtml($testers);
                    $adminPlan['testers'] = $optionsHtml;
                    $allAdmin[]           = $adminPlan;
                }

                $plans['admin_created_plans'] = $allAdmin;
                break;
            }
        }

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