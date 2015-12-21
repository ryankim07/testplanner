<?php namespace App\Http\Controllers;

/**
 * Class DashboardController
 *
 * Dashboard Controller
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2015 mophie (https://lpp.nophie.com)
 */

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

use App\Plans;
use Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show all the plans assigned to user
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $userRoles               = Auth::user()->role()->get();
        $plans['plans_assigned'] = Plans::getPlansAssigned();

        foreach($userRoles as $role) {
            if ($role->name == "Administrator") {
                // Display administrator dashboard
                $plans['admin_created_plans'] = Plans::getAdminCreatedPlans($role->id);
                break;
            }
        }

        // Return view
        return view('pages.main.dashboard', ['plans' => array_filter($plans)]);
    }

    /**
     * Display or edit plan
     *
     * @param $planId
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function view($planId, $userId)
    {

    }

    /**
     * Display or edit plan
     *
     * @param $planId
     * @param $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond($planId, $userId)
    {
        $plan = Plans::getPlanResponses($planId, $userId);

        return view('pages.testplanner.plan_response', ['plan' => $plan]);
    }
}