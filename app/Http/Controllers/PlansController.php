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

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PlansFormRequest;
use App\Http\Requests\ReviewFormRequest;
use App\Http\Requests\UserResponseFormRequest;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use PhpSpec\Exception\Exception;

use App\Facades\Email;
use App\Facades\Jira;

use App\Plans;
use App\Tickets;
use App\Testers;
use App\TicketsResponses;
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
        $versions = $this->_Jira();

        return view('pages.testplanner.step_1', [
            'mode'     => 'build',
            'userId'   => $user->id,
            'versions' => json_encode($versions)
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
        $versions = $this->_Jira();

        return view('pages.testplanner.step_1', [
            'mode'     => 'edit',
            'planData' => $planData,
            'versions' => json_encode($versions)
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
        // Save plan data to session
        Session::put('mophie_testplanner.plan', array_except($request->all(), ['_token', '_method']));

        return redirect()->action('PlansController@review');
    }

    /**
     * Update all the plan details
     *
     * @param $planId
     * @param Request $request
     */
    public function updatePlanDetails($planId, Request $request)
    {
        $plan = Plans::find($planId);
        $plan->update(['description' => $request->get('description')]);
    }

    /**
     * View plan
     *
     * @param $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function view($id)
    {
        $plan       = Plans::find($id);
        $allTickets = $plan->tickets()->get();
        $allTesters = $plan->testers()->get();

        foreach($allTickets as $ticket) {
            $ticketDetails = unserialize($ticket->tickets);

            foreach($ticketDetails as $detail) {
                $tickets[$ticket->id] = $detail;
            }
        }

        foreach($allTesters as $tester) {
            $testers[$tester->id] = [
                'id'         => $tester->tester_id,
                'first_name' => User::getUserFirstName($tester->tester_id),
                'browser'    => $tester->browser
            ];
        }

        return view('pages.testplanner.view', [
            'plan'    => [
                'id'          => $plan->id,
                'description' => $plan->description,
                'tickets'     => $tickets,
                'testers'     => $testers
            ]
        ]);
    }

    /**
     * Show all plans or plans created by an administrator
     *
     * @param $userId
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function viewAllPlans($userId)
    {
        $userRoles = Auth::user()->role()->get();
        $sorting   = Tables::sorting();
        $table     = Tables::prepareTable($sorting['order'], [
            'description',
            'creator',
            'status',
            'created_at',
            'updated_at'
        ], 'PlansController@index');

        $query = '';
        foreach($userRoles as $role) {
            if ($role->name == "administrator") {
                $query = Plans::getAllPlans($sorting['sortBy'], $sorting['order'], $userId);
                break;
            }
        }

        return view('pages.testplanner.view_all_admin', [
            'userId'      => isset($userId) ? $userId : 0,
            'plans'       => isset($query) ? $query->paginate(config('testplanner.pagination_count')) : '',
            'totalPlans'  => isset($query) ? Plans::count() : 0,
            'columns'     => $table['columns'],
            'columnsLink' => $table['columns_link'],
            'link'        => ''
        ]);
    }

    public function search()
    {

    }

    /**
     * Store a newly created resource in storage
     *
     * @param PlansFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function store(PlansFormRequest $request)
    {
        // Save Plan data to session
        Session::put('mophie_testplanner.plan', array_except($request->all(), '_token'));

        return redirect('ticket/build');
    }

    /**
     * View response by plan and user ID
     *
     * @param $planId
     * @param $userId
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function viewResponse($planId, $userId)
    {
        $plan       = Plans::getPlanResponses($planId, $userId);
        $allTesters = Testers::getTestersByPlanId($planId);

        $browserTesters[''] = 'View other responses';
        foreach ($allTesters as $eachTester) {
            $browserTesters[$eachTester->id] = $eachTester->first_name;
        }

        return view('pages.testplanner.view_response', [
            'userId'  => $userId,
            'plan'    => $plan,
            'testers' => $browserTesters
        ]);
    }

    /**
     * Display or edit plan
     *
     * @param $planId
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function respond($planId)
    {
        $user = Auth::user();
        $plan = Plans::getPlanResponses($planId, $user->id);

        return view('pages.testplanner.respond', ['plan' => $plan]);
    }

    /**
     * Show complete information of all forms filled on each
     * registration step
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function review()
    {
        // Session data
        $data = [
            'plan'    => Session::get('mophie_testplanner.plan'),
            'tickets' => Session::get('mophie_testplanner.tickets'),
            'testers' => Session::get('mophie_testplanner.testers')
        ];

        return view('pages.testplanner.review', $data);
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
        $redirect    = false;
        $errorMsg    = '';

        // Start transaction
        DB::beginTransaction();

        // Start plan creation
        try {
            // Save new plan build
            $plan = Plans::create($planData);
            $planId = $plan->id;
            $planData['id'] = $planId;

            if (isset($plan->id)) {
                // Save new tickets
                Tickets::create([
                    'plan_id' => $planId,
                    'tickets' => serialize($ticketsData)
                ]);

                // Save new testers
                foreach($testerData as $tester) {
                    Testers::create([
                        'plan_id'   => $planId,
                        'tester_id' => $tester['id'],
                        'browser'   => $tester['browser']
                    ]);

                    // Create object for email
                    $testersWithEmail[] = [
                        'tester_id'  => $tester['id'],
                        'first_name' => $tester['first_name'],
                        'browser'    => $tester['browser'],
                        'email'      => User::getUserEmail($tester['id'])
                    ];
                }
            }
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            $redirect = true;
        } catch (ValidationException $e) {
            $errorMsg = $e->getErrors();
            $redirect = true;
        } catch (QueryException $e) {
            $errorMsg = $e->getErrors();
            $redirect = true;
        } catch (ModelNotFoundException $e) {
            $errorMsg = $e->getErrors();
            $redirect = true;
        }

        // Redirect if errors
        if ($redirect) {
            // Rollback
            DB::rollback();

            // Log to system
            Utils::log($errorMsg, array_merge($planData, $ticketsData, $testerData));

            // Delete session
            Session::forget('mophie_testplanner');

            return redirect()->action('PlansController@build')
                ->with('flash_message', config('testplanner.plan_build_error'));
        }

        // Commit all changes
        DB::commit();

        // Log to activity stream
        ActivityStream::saveActivityStream($planData, 'plan');

        // Mail all test browsers
        Email::sendEmail('plan-created', array_merge($planData, array('testers' => $testersWithEmail)));

        // Delete session
        Session::forget('mophie_testplanner');

        return redirect('dashboard');
    }

    /**
     * Save user's plan response
     *
     * @param UserResponseFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function saveUserResponse(UserResponseFormRequest $request)
    {
        $res      = array_except($request->all(), '_token');
        $planData = json_decode($res['plan'], true);
        $tickets  = json_decode($res['tickets_obj'], true);
        $planData['tickets_responses'] = $tickets;

        // Start transaction
        DB::beginTransaction();

        try {
            // Save ticket response
            $response = TicketsResponses::saveTicketResponse($planData);

        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            $redirect = true;
        } catch (ValidationException $e) {
            $errorMsg = $e->getErrors();
            $redirect = true;
        } catch (QueryException $e) {
            $errorMsg = $e->getErrors();
            $redirect = true;
        } catch (ModelNotFoundException $e) {
            $errorMsg = $e->getErrors();
            $redirect = true;
        }

        // Commit all changes
        DB::commit();

        // Redirect if errors
        /*if ($redirect) {
            // Rollback
            DB::rollback();

            // Log to system
            Utils::log($errorMsg, $mergedData);

            return redirect()->action('PlansController@index')->withInput()
       ->withErrors(array('message' => 'Login field is required.'));
        }*/

        // Log activity
        ActivityStream::saveActivityStream($planData, 'ticket-response', $response);

        // Mail all test browsers
        /*if ($planStatus == 'complete') {
            // Create object for email
            Email::sendEmail('ticket-responded', [
                'ticket_resp_id'    => $resp['ticket_resp_id'],
                'plan_desc'         => $planData['description'],
                'tester_id'         => $planData['tester_id'],
                'tester_first_name' => $planData['assignee'],
                'email'             => $user->email,
                'ticket_status'     => $ticketStatus,
                'tickets'           => serialize($tickets))
            ]);
        }*/

        return redirect('dashboard');
    }

    /**
     * Use Jira API
     *
     * @return array
     */
    private function _Jira()
    {
        // Get JIRA project versions
        $results  = Jira::getAllProjectVersions('ECOM');
        $versions = [];

        if (isset($results)) {
            foreach($results as $version) {
                $versions[] = 'Test Plan for build v' . $version['name'];
            }
        }

        return $versions;
    }
}