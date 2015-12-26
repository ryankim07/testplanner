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
        // Get user's role
        $userRoles = Auth::user()->role()->get();

        // Get assigned plans
        $assignedResponses = Plans::getPlansAssignedResponses('created_at', 'DESC', 'dashboard');

        foreach($assignedResponses->get() as $plan) {
            $results[] = get_object_vars($plan);
        }

        $plans['plans_assigned'] = $results;

        // Display administrator dashboard
        foreach($userRoles as $role) {
            if ($role->name == "Administrator") {
                $adminCreated = Plans::getAdminCreatedPlansResponses($role->id, 'created_at', 'DESC', 'dashboard');

                foreach ($adminCreated->get() as $plan) {
                    $allTesters = Testers::getTestersByPlanId($plan->id);

                    foreach ($allTesters as $tester) {
                        $browserTester[$tester->id] = $tester->first_name;
                    }

                    $plan = get_object_vars($plan);
                    $plan['testers'] = $browserTester;
                    $allAdmin[] = $plan;
                }

                $plans['admin_created_plans'] = $allAdmin;
                break;
            }
        }

        // Get activity stream
        $activityStream = ActivityStream::getActivityStream();

        // Return view
        return view('pages.main.dashboard', [
            'activities' => $activityStream,
            'plans'      => array_filter($plans)
        ]);
    }


    public function assigned()
    {
        $userRoles = Auth::user()->role()->get();

        // Display all plans
        $query = '';
        foreach($userRoles as $role) {
            if ($role->name == "Administrator") {
                $sorting = Tables::sorting();
                $table   = Plans::prepareTable($sorting['order'], [
                    'description',
                    'first_name',
                    'status',
                    'created_at',
                    'updated_at'
                ]);
                $query = Plans::getPlansAssignedResponses($sorting['sortBy'], $sorting['order']);
                break;
            }
        }

        return view('pages.testplanner.view_all_assigned', [
            'plans'       => isset($query) ? $query->paginate(config('testplanner.pagination_count')) : '',
            'totalPlans'  => isset($query) ? Plans::count() : 0,
            'columns'     => $table['columns'],
            'columnsLink' => $table['columns_link'],
            'link'        => ''
        ]);
    }

    /**
     * Create comment in activity stream
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function comment(Request $request)
    {
        $user = Auth::user();
        $res  = array_except($request->all(), '_token');

        ActivityComments::saveActivityComment($res['id'], $user->id, $res['comment']);

        return response()->json(["status" => "sucess"]);
    }
}