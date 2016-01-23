<?php namespace App\Http\Controllers;

/**
 * Class PlansController
 *
 * Controller
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PlansFormRequest;
use App\Http\Requests\PlanUpdateFormRequest;

use App\Facades\Tools;
use App\Facades\Email;

use App\Plans;
use App\Tickets;
use App\Testers;
use App\ActivityStream;
use App\User;
use App\Tables;

use Auth;
use Session;

class PlansController extends Controller
{
    const CONSTANT = 'constant value';

    /**
     * PlansController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('testplanner', [
            'only' => ['build', 'edit', 'update', 'review', 'save']
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function index()
    {
        return redirect('dashboard');
    }

    /**
     * Show the form for creating a new resource
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function build()
    {
        // Current user
        $user = Auth::user();

        // Get Jira versions
        $jiraVersions = Tools::jiraVersions();

        return view('pages.testplanner.step_1', [
            'mode'          => 'build',
            'userId'        => $user->id,
            'jira_versions' => json_encode($jiraVersions)
        ]);
    }

    /**
     * Show the form for editing the specified resource
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function edit()
    {
        // Get plan session data
        $planData = Session::get('mophie_testplanner.plan');

        // Get Jira versions
        $jiraVersions = Tools::jiraVersions();

        return view('pages.testplanner.step_1', [
            'mode'          => 'edit',
            'planData'      => $planData,
            'jira_versions' => json_encode($jiraVersions)
        ]);
    }

    /**
     * Update the specified resource in storage
     *
     * @param PlansFormRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PlansFormRequest $request)
    {
        // Save data to session
        Session::put('mophie_testplanner.plan', array_except($request->all(), ['_token', '_method']));

        return redirect()->action('PlansController@review');
    }

    /**
     * Store a newly created resource in storage
     *
     * @param PlansFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function store(PlansFormRequest $request)
    {
        // Save data to session
        Session::put('mophie_testplanner.users', User::all()->toArray());
        Session::put('mophie_testplanner.plan', array_except($request->all(), '_token'));

        return redirect('ticket/build');
    }

    /**
     * Update built plan
     *
     * @param $planId
     * @param PlanUpdateFormRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateBuiltPlan($planId, PlanUpdateFormRequest $request)
    {
        $testerData = $request->get('browser_testers');

        $planData      = Plans::updateBuiltPlanDetails($planId, $request);
        $ticketsUpdate = Tickets::updateBuiltTickets($planId, $request->get('tickets_obj'));
        $testersUpdate = Testers::updateBuiltTesters($planId, $testerData);

        if ((count($planData) > 0) && $ticketsUpdate && $testersUpdate) {
            // Log to activity stream
            ActivityStream::saveActivityStream($planData, 'plan', 'update');

            // Mail all test browsers
            Email::sendEmail('plan-updated', array_merge([
                'plan_id'     => $planId,
                'description' => $request->get('description')], [
                    'testers' => $testerData
                ]
            ));

            $msg = config('testplanner.messages.plan.build_update_error');
        } else {
            $msg = config('testplanner.messages.plan.build_update');
        }

        return redirect('dashboard')->with('flash_success', $msg);
    }

    /**
     * View plan for editing
     *
     * @param $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function view($id)
    {
        $plan    = Plans::find($id);
        $tickets = unserialize($plan->tickets()->first()->tickets);

        // Render tickets
        $ticketsHtml = '';
        foreach($tickets as $ticket) {
            $ticketsHtml .= view('pages/testplanner/partials/tickets', [
                'mode'             => 'edit',
                'ticket'           => $ticket,
                'addTicketBtnType' => 'btn-custom'
            ])->render();
        }

        // Get Jira versions
        $jiraVersions = Tools::jiraVersions();

        // Get Jira issues
        $jiraIssues = Tools::jiraIssues();

        return view('pages.testplanner.view', [
            'plan' => [
                'id'            => $plan->id,
                'description'   => $plan->description,
                'started_at'    => Tools::dateConverter($plan->started_at),
                'expired_at'    => Tools::dateConverter($plan->expired_at),
                'tickets_html'  => $ticketsHtml,
                'users'         => User::all()->toArray(),
                'testers'       => json_encode($plan->testers()->get()->toArray()),
                'jira_versions' => json_encode($jiraVersions),
                'jira_issues'   => json_encode($jiraIssues)
            ]
        ]);
    }

    /**
     * Show all plans created by administrator/s
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function viewAllCreated(Request $request)
    {
        $user  = Auth::user();
        $roles = $user->role()->get();

        // If user has root privileges, get all the plans that were created.
        // Otherwise just get the plans created with administrator privilege.
        foreach($roles as $role) {
            $roleName = $role->name;
            $userId = $roleName == "root" ? 0 : $user->id;
            break;
        }

        // Display selected creator of the plan
        $adminId = $request->get('admin');
        if (isset($adminId)) {
            $userId = $adminId;
        }

        // Administrators who created plans
        $admins = User::getAllUsersByRole($roleName);

        // Set up dropdown list of all admins
        $adminsList[0] = 'All';
        foreach($admins as $admin) {
            $adminsList[$admin->id] = $admin->first_name;
        }

        // Prepare columns to be shown
        $table = Tables::prepare('order', [
            'description',
            'first_name',
            'last_name',
            'status',
            'created_at',
            'updated_at',
            'edit'
        ], 'PlansController@index');

        $query = Plans::getAllPlans($table['sorting']['sortBy'], $table['sorting']['order'], $userId);

        return view('pages.testplanner.view_all_created', [
            'userId'      => $userId,
            'role'        => $roleName,
            'plans'       => isset($query) ? $query->paginate(config('testplanner.tables.pagination.lists')) : '',
            'totalPlans'  => isset($query) ? Plans::count() : 0,
            'columns'     => $table['columns'],
            'columnsLink' => $table['columns_link'],
            'link'        => '',
            'adminsList'  => isset($adminsList) ? $adminsList : ''
        ]);
    }

    /**
     * Show all assigned plans created by other administrators
     * that needs to be tested by logged user
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function viewAllAssigned()
    {
        $user  = Auth::user();

        // Prepare columns to be shown
        $table = Tables::prepare('order', [
            'description',
            'first_name',
            'last_name',
            'status',
            'created_at',
            'updated_at',
            'respond'
        ], 'PlansController@index');

        $query = Plans::getAllAssigned($user->id, $table['sorting']['sortBy'], $table['sorting']['order']);

        return view('pages.testplanner.view_all_assigned', [
            'plans'       => !empty($query) ? $query->paginate(config('testplanner.tables.pagination.lists')) : '',
            'totalPlans'  => !empty($query) ? Plans::count() : 0,
            'columns'     => $table['columns'],
            'columnsLink' => $table['columns_link'],
            'link'        => ''
        ]);
    }

    /**
     * Display all plans that were tested
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function viewAllResponses()
    {
        $user  = Auth::user();

        // Prepare columns to be shown
        $table = Tables::prepare('order', [
            'description',
            'status',
            'created_at',
            'updated_at',
            'testers',
            'view'
        ], 'PlansController@index');

        $query       = Plans::getAllResponses($user->id, $table['sorting']['sortBy'], $table['sorting']['order']);
        $optionsHtml = [];

        // Setup up dropdown list of testers
        foreach ($query->get() as $plan) {
            $testers                  = Plans::getTestersByPlanId($plan['id']);
            $optionsHtml[$plan['id']] = Tools::dropDownOptionsHtml($testers);
        }

        return view('pages.testplanner.view_all_responses', [
            'plans'       => !empty($query) ? $query->paginate(config('testplanner.tables.pagination.lists')) : '',
            'totalPlans'  => !empty($query) ? Plans::count() : 0,
            'testers'     => $optionsHtml,
            'columns'     => $table['columns'],
            'columnsLink' => $table['columns_link'],
            'link'        => ''
        ]);
    }

    /**
     * View response by plan and user ID
     *
     * @param $planId
     * @param $selectedUserId
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function response($planId, $selectedUserId)
    {
        // Plan details
        $planDetails = Plans::find($planId);

        // Show other users that might have submitted responses
        $testers = Plans::getTestersByPlanId($planId);

        $mode           = 'response';
        $tabHeaderHtml  = '';
        $tabBodyHtml    = '';
        $totalResponses = 0;

        foreach ($testers as $tester) {
            $testerId        = $tester->user_id;
            $testerFirstName = $tester->user_first_name;

            // Render users tab
            $tabHeaderHtml .= view('pages/testplanner/partials/response_respond/tab_header_users', [
                'selectedUserId'  => $selectedUserId,
                'testerId'        => $testerId,
                'testerFirstName' => $testerFirstName
            ])->render();

            // Render plan detais
            $plan = Plans::getTesterPlanResponse($planId, $testerId);

            if (!empty($plan['ticket_resp_id'])) {
                $totalResponses++;
            }

            $tabBodyHtml .= view('pages/testplanner/partials/response_respond/tab_body', [
                'mode'            => $mode,
                'selectedUserId'  => $selectedUserId,
                'testerId'        => $testerId,
                'testerFirstName' => $testerFirstName,
                'plan'            => $plan,
                'totalResponses'  => $totalResponses,
                'browsers'        => Tools::getTesterBrowserImg($tester->browsers)
            ])->render();
        }

        return view('pages.testplanner.response_respond_main', [
            'mode'           => $mode,
            'plan'           => ['description' => $planDetails->description],
            'tabHeaderHtml'  => $tabHeaderHtml,
            'tabBodyHtml'    => $tabBodyHtml
        ]);
    }

    /**
     * Respond to the plan
     *
     * @param $planId
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function respond($planId)
    {
        $user     = Auth::user();
        $plan     = Plans::getTesterPlanResponse($planId, $user->id);
        $mode     = 'respond';
        $planHtml = '';

        $planHtml .= view('pages/testplanner/partials/response_respond/plan_details', ['plan' => $plan])
            ->render();

        $planHtml .= view('pages/testplanner/partials/response_respond/plan_tickets', [
            'mode' => $mode,
            'plan' => $plan
        ])->render();

        return view('pages.testplanner.response_respond_main', [
            'mode'     => $mode,
            'plan'     => $plan,
            'planHtml' => $planHtml,
        ]);
    }

    /**
     * Show complete information of all forms filled on each
     * registration step
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function review()
    {
        // Get from session data
        return view('pages.testplanner.review', [
            'plan'    => Session::get('mophie_testplanner.plan'),
            'tickets' => Session::get('mophie_testplanner.tickets'),
            'users'   => Session::get('mophie_testplanner.users'),
            'testers' => json_encode(Session::get('mophie_testplanner.testers'))
        ]);
    }

    /**
     * Save plan build
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function save()
    {
        // Retrieve session data
        $planData    = Session::get('mophie_testplanner.plan');
        $ticketsData = Session::get('mophie_testplanner.tickets');
        $testerData  = Session::get('mophie_testplanner.testers');

        // Save plan
        $planId = Plans::savePlan($planData, $ticketsData, $testerData);
        $planData['plan_id'] = $planId;

        if (!$planId) {
            // Delete session
            Session::forget('mophie_testplanner');

            return redirect()->action('PlansController@review')
                ->withInput()
                ->withErrors(['message' => config('testplanner.messages.plan.build_error')]);
        }

        // Log to activity stream
        ActivityStream::saveActivityStream($planData, 'plan', 'new');

        // Mail all test browsers
        Email::sendEmail('plan-created', array_merge($planData, ['testers' => $testerData]));

        // Delete session
        Session::forget('mophie_testplanner');

        return redirect('dashboard')->with('flash_success', config('testplanner.messages.plan.new_build'));
    }

    /**
     * Search functionality for each table
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function search(Request $request)
    {
        $user  = Auth::user();
        $roles = $user->role()->get();

        // If user has root privileges, get all the plans that were created.
        // Otherwise just get the plans created with administrator privilege.
        foreach($roles as $role) {
            $roleName = $role->name;
            $userId = $roleName == "root" ? 0 : $user->id;
            break;
        }

        // Display selected creator of the plan
        $adminId = $request->get('admin');
        if (isset($adminId)) {
            $userId = $adminId;
        }

        // Administrators who created plans
        $admins = User::getAllUsersByRole($roleName);

        // Set up dropdown list of all admins
        $adminsList[0] = 'All';
        foreach($admins as $admin) {
            $adminsList[$admin->id] = $admin->first_name;
        }

        $query   = DB::table('plans AS p');
        $results = Tables::searchResults($query);

        // Prepare columns to be shown
        $table   = Tables::prepare('order', [
            'description',
            'first_name',
            'last_name',
            'status',
            'created_at',
            'updated_at',
            'edit'
        ], 'PlansController@index');

        return view('pages.testplanner.view_all_created', [
            'userId'      => $userId,
            'role'        => $roleName,
            'plans'       => isset($results['list']) ? $results['list'] : '',
            'totalPlans'  => isset($results['totalCount']) ? $results['totalCount'] : 0,
            'columns'     => $table['columns'],
            'columnsLink' => $table['columns_link'],
            'link'        => $results['link'],
            'adminsList'  => isset($adminsList) ? $adminsList : ''
        ]);
    }
}