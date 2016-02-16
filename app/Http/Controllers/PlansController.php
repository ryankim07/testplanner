<?php namespace App\Http\Controllers;

/**
 * Class PlansController
 *
 * Controller
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://tp.nophie.us)
 */

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PlansFormRequest;
use App\Http\Requests\PlanUpdateFormRequest;

use App\Facades\Tools;

use App\Events\SavingPlan,
    App\Events\UpdatingPlan;

use App\Api\UserApi,
    App\Api\PlansApi,
    App\Api\TestersApi,
    App\Api\TicketsApi,
    App\Api\TablesApi,
    App\Api\JiraApi;

use Auth;
use Session;

class PlansController extends Controller
{
    /**
     * @var User Api
     */
    protected $userApi;

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
     * @var Tables Api
     */
    protected $tablesApi;

    /**
     * @var JiraApi
     */
    protected $jiraApi;

    /**
     * @var User Auth
     */
    protected $user;

    /**
     * PlansController constructor.
     */
    public function __construct(PlansApi $plansApi, UserApi $userApi, TestersApi $testersApi,
                                TicketsApi $ticketsApi, TablesApi $tablesApi, JiraApi $jiraApi)
    {
        $this->middleware('auth');
        $this->middleware('testplanner', [
            'only' => ['build', 'edit', 'update', 'review', 'save']
        ]);

        $this->userApi    = $userApi;
        $this->plansApi   = $plansApi;
        $this->testersApi = $testersApi;
        $this->ticketsApi = $ticketsApi;
        $this->tablesApi  = $tablesApi;
        $this->jiraApi    = $jiraApi;
        $this->user       = Auth::user();
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
        // Get Jira versions
        $jiraVersions = $this->jiraApi->jiraVersions();

        return view('pages.testplanner.step_1', [
            'mode'          => 'build',
            'userId'        => $this->user->id,
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
        $jiraVersions = $this->jiraApi->jiraVersions();

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
       Session::put('mophie_testplanner.users', $this->userApi->usersList());
       Session::put('mophie_testplanner.plan', array_except($request->all(), '_token'));

        return redirect('ticket/build');
    }

    /**
     *  View plan for editing
     *
     * @param $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function view($id)
    {
        $plan = $this->plansApi->viewPlan($id, $this->userApi, $this->jiraApi);

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
        $plans = $this->plansApi->getAllAssigned($this->user->id, 'created_at', 'DESC');

        return view('pages.testplanner.view_all_assigned', $plans);
    }

    /**
     * Display all plans that were tested
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function viewAllResponses()
    {
        $plans = $this->plansApi->getAllResponses($this->user->id, 'created_at', 'DESC');

        return view('pages.testplanner.view_all_responses', $plans);
    }

    /**
     * View response by plan and user ID.  This controller will only render
     * the side stacked pills
     *
     * @param $planId
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function response($planId)
    {
        $response = $this->plansApi->responses($planId);

        return view('pages.testplanner.responses', $response);
    }

    /**
     * Respond to the plan
     *
     * @param $planId
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function respond($planId)
    {
        $respond = $this->plansApi->respond($planId, $this->user->id);

        Session::put('mophie.plan',array_merge(['type' => 'ticket-response'], array_only($respond['plan'], [
            'plan_id', 'creator_id', 'description', 'tester_id', 'reporter', 'assignee', 'ticket_status']))
        );

        return view('pages.testplanner.respond', $respond);
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
            'plan'    =>Session::get('mophie_testplanner.plan'),
            'tickets' =>Session::get('mophie_testplanner.tickets'),
            'users'   =>Session::get('mophie_testplanner.users'),
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
        $saveData = $this->plansApi->savePlan($planData, $ticketsData, $testerData);

        // Delete session
       Session::forget('mophie_testplanner');

        if (!$saveData) {
            return redirect()->action('PlansController@review')
                ->withInput()
                ->withErrors(['message' => config('testplanner.messages.plan.build_error')]);
        }

        // Send notifications observer
        event(new SavingPlan($saveData));

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
        $ticketsObj     = json_decode($request->get('tickets_obj'), true);
        $testersBrowser = json_decode($request->get('browser_testers'), true);
        $origData       = json_decode($request->get('orig_data'), true);
        $planData       = $this->plansApi->updateBuiltPlan($planId, $request);
        $ticketsUpdate  = $this->ticketsApi->updateBuiltTickets($planId, $ticketsObj);
        $testersUpdate  = $this->testersApi->updateBuiltTesters($planId, $testersBrowser, $origData);

        $msg = config('testplanner.messages.plan.build_update_error');

        if ((count($planData) > 0) && (count($testersUpdate) > 0) && $ticketsUpdate) {
            // Send notifications observer
            event(new updatingPlan(array_merge($planData, ['testers' => $testersUpdate])));

            $msg = $planData['description'] . ' ' . config('testplanner.messages.plan.build_update');
        }

        return redirect('dashboard')->with('flash_success', $msg);
    }

    /**
     * All created plans search functionality
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function searchAllCreated(Request $request)
    {
        $searchTerms = array_except($request->all(), ['_token', 'admin']);
        $adminId     = $request->get('admin');
        $roles       = Session::get('mophie.user.roles');

        $results = $this->plansApi->prepareSearchAllCreated($searchTerms, $roles, $adminId);

        return view('pages.testplanner.view_all_created', $results);
    }

    /**
     * All assigned plans search functionality
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function searchAllAssigned(Request $request)
    {
        $searchTerms = array_except($request->all(), ['_token', 'admin']);

        $columns = [
            'description',
            'first_name',
            'last_name',
            'created_at',
            'updated_at',
            'respond'
        ];

        $results = $this->tablesApi->searchPlans($searchTerms, $columns);

        return view('pages.testplanner.view_all_assigned', $results);
    }

    /**
     * All plans responses search functionality
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function searchAllResponses(Request $request)
    {
        $searchTerms = array_except($request->all(), ['_token', 'admin']);

        $columns = [
            'description',
            'status',
            'created_at',
            'updated_at',
            'view'
        ];

        $results = $this->tablesApi->searchPlans($searchTerms, $columns);

        return view('pages.testplanner.view_all_responses', $results);
    }
}