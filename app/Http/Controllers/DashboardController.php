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
use Illuminate\Support\Facades\DB;

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
        $planStatus   = Dashboard::displayPlanStatus();
        $ticketStatus = Dashboard::displayTicketStatus();

        // Return view
        return view('pages.main.dashboard', [
            'planStatus'   => $planStatus,
            'ticketStatus' => $ticketStatus
        ]);
    }
}