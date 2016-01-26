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

use App\Api\PlansApi;/*
use App\Tickets;
use App\Testers;
use App\ActivityStream;*/

use App\Models\User;

use Auth;
use Session;

class PlansController extends Controller
{
    /**
     * @var User
     */
    protected $userModel;

    /**
     * @var Plans Api
     */
    protected $plansApi;

    /**
     * @var Testers Api
     */
    protected $testersApi;

    /**
     * @var Tickets Api
     */
    protected $ticketsApi;

    /**
     * PlansController constructor.
     */
    public function __construct(PlansApi $plansApi, User $user, TestersApi $testersApi, TicketsApi $ticketsApi)
    {
        $this->middleware('auth');
        $this->middleware('testplanner', [
            'only' => ['build', 'edit', 'update', 'review', 'save']
        ]);

        $this->userModel  = $user;
        $this->plansApi   = $plansApi;
        $this->testersApi = $testersApi;
        $this->ticketsApi = $ticketsApi;
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
        Session::put('mophie_testplanner.users', $this->userModel->all()->toArray());
        Session::put('mophie_testplanner.plan', array_except($request->all(), '_token'));

        return redirect('ticket/build');
    }

    /**
     * View plan for editing
     *
     * @param $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function view($id)
    {
        $plan = $this->plansApi->viewPlan($id);

        return view('pages.testplanner.view', $plan);
    }

    /**
     * Show all plans created by administrator/s
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function viewAllCreated(Request $request)
    {
        $adminId = $request->get('admin');
        $plans   = $this->plansApi->getAllCreated($adminId);

        return view('pages.testplanner.view_all_created', $plans);
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
        $plans = $this->plansApi->getAllAssigned($user->id, 'created_at', 'DESC');

        return view('pages.testplanner.view_all_assigned', $plans);
    }

    /**
     * Display all plans that were tested
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function viewAllResponses()
    {
        $user  = Auth::user();
        $plans = $this->plansApi->getAllResponses($user->id, 'created_at', 'DESC');

        return view('pages.testplanner.view_all_responses', $plans);
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
        $response = $this->plansApi->viewResponse($planId, $selectedUserId);

        return view('pages.testplanner.response_respond_main', $response);
    }

    /**
     * Respond to the plan
     *
     * @param $planId
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function respond($planId)
    {
        $user    = Auth::user();
        $respond = $this->plansApi->respond($planId, $user->id);

        return view('pages.testplanner.response_respond_main', $respond);
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
        $planId    = $this->plansApi->savePlan($planData, $ticketsData, $testerData);
        $planData += ['plan_id' => $planId];

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
     * Update built plan
     *
     * @param $planId
     * @param PlanUpdateFormRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateBuiltPlan($planId, PlanUpdateFormRequest $request)
    {
        $testerData = $request->get('browser_testers');

        $planData      = $this->plansApi->updateBuiltPlanDetails($planId, $request);
        $ticketsUpdate = $this->ticketsApi->updateBuiltTickets($planId, $request->get('tickets_obj'));
        $testersUpdate = $this->testersApi->updateBuiltTesters($planId, $testerData);

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
        $admins = $this->userModel->getAllUsersByRole($roleName);

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