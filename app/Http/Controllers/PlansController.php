<?php namespace App\Http\Controllers;

/**
 * Class PlansController
 *
 * Controller
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
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use PhpSpec\Exception\Exception;
use App\Http\Requests\PlansFormRequest;
use App\Http\Requests\ReviewFormRequest;
use App\Http\Requests\UserResponseFormRequest;

use App\Facades\Email;

use App\Plans;
use App\Tickets;
use App\Testers;
use App\TicketsResponses;
use App\User;

use Auth;
use Session;

class PlansController extends Controller
{
    const CONSTANT = 'constant value';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource
     *
     * @return \Illuminate\View\View|Redirect
     */
    public function build()
    {
        $userId = Auth::user();

        return view('pages.testplanner.plan_build', ['userId' => $userId->id]);
    }

    /**
     * View plan
     *
     * @param $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function view($id)
    {
        $plan = Plans::findOrFail($id);

        return view('pages.testplanner.plan_view', $plan);
    }

    /**
     * Show all plans
     *
     * @return array|\Illuminate\View\View|mixed
     */
    public function viewAll()
    {
        $plans = Plans::all();

        return view('pages.testplanner.view_all_plans', ['plans' => $plans]);
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
     * Show complete information of all forms filled on each
     * registration step
     *
     * @return \Illuminate\View\View
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
     * Finalize Plan setup
     *
     * @param ReviewFormRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(ReviewFormRequest $request)
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

            // Save new tickets build
            if (isset($plan->id)) {
                foreach ($ticketsData as $ticket) {
                    $ticket = Tickets::create([
                        'plan_id'     => $plan->id,
                        'description' => $ticket['description'],
                        'objective'   => $ticket['objective'],
                        'test_steps'  => $ticket['test_steps']
                    ]);
                }
            }

            // Save testers build
            foreach($testerData as $tester) {
                Testers::create([
                    'plan_id'   => $plan->id,
                    'tester_id' => $tester['id'],
                    'browser'   => $tester['browser']
                ]);

                // Create object for email
                $testersWithEmail[] = [
                    'plan_desc'  => $plan->description,
                    'tester_id'  => $tester['id'],
                    'first_name' => $tester['first_name'],
                    'browser'    => $tester['browser'],
                    'email'      => $tester['email']
                ];
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
        /*if ($redirect) {
            // Rollback
            DB::rollback();

            // Log to system
            Utils::log($errorMsg, $mergedData);

            // Delete session
            Session::forget('mophie_h2pro');

            return redirect()->action('RegistrationController@index')
                ->with('flash_message', config('h2pro.registration_problems'));
        }*/

        // Commit all changes
        DB::commit();

        // Mail all test browsers
        //Email::sendEmail('plan-created', array_merge(array('plan' => $planData), array('testers' => $testersWithEmail)));

        //return view('pages.testplanner.plan_thankyou');
    }

    /**
     * Tester's response
     *
     * @param $id
     * @return array|\Illuminate\View\View|mixed
     */
    public function response($id)
    {
        $plan = Plans::renderPlan($id);
        $plan['tester_id'] = Auth::user()->id;

        return view('pages.testplanner.response', $plan);
    }

    /**
     * Save user's plan response
     *
     * @param UserResponseFormRequest $request
     */
    public function saveUserResponse(UserResponseFormRequest $request)
    {
        $res        = array_except($request->all(), '_token');
        $tickets    = json_decode($res['tickets-obj'], true);
        $planStatus = 'complete';

        foreach($tickets as $ticket) {
            if (!isset($ticket['status'])) {
                $planStatus = 'incomplete';
            }
        }

        // Start transaction
        DB::beginTransaction();

        // Start plan creation
        try {
            $plan = Plans::find($res['plan_id']);
            $user = $user = Auth::user();

            // Add entry to tickets responses table
            $ticketResponse = TicketsResponses::create([
                'plan_id'   => $plan->id,
                'tester_id' => $user->id,
                'status'    => $planStatus,
                'tickets'   => serialize($tickets)
            ]);
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
       // DB::commit();

        // Redirect if errors
        /*if ($redirect) {
            // Rollback
            DB::rollback();

            // Log to system
            Utils::log($errorMsg, $mergedData);

            return redirect()->action('PlansController@index')
                ->with('flash_message', config('h2pro.registration_problems'));
        }*/

        // Mail all test browsers
        if ($planStatus == 'complete') {
            // Create object for email
            $ticket = [
                'ticket_resp_id'    => $ticketResponse->id,
                'plan_desc'         => $plan->description,
                'tester_id'         => $user->id,
                'tester_first_name' => $user->first_name,
                'email'             => $user->email,
                'status'            => $planStatus,
                'tickets'           => serialize($tickets)
            ];

            Email::sendEmail('ticket-responded', $ticket);
        }

        //return view('pages.testplanner.plan_response_thankyou');
    }
}