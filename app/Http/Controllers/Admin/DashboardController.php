<?php namespace App\Http\Controllers\Admin;

/**
 * Class DashboardController
 *
 * Admin Controller
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2015 mophie (https://lpp.nophie.com)
 */

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

use App\Facades\Utils;

use App\Dashboard;

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
     * Show the application dashboard to the user.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $adminPlans = Dashboard::getAdminPlans();

        // Return view
        return view('pages.admin.dashboard', ['adminPlans' => $adminPlans]);
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
        $response = Dashboard::getTesterResponse($planId, $userId);

        $viewHtml = view('pages.admin.response_view', [
            'op'   => 'view',
            'plan' => $response
        ])->render();

        return response()->json([
            "editTitle" => '',
            "viewBody"  => $viewHtml,
            "editBody"  => ''
        ]);
    }
}