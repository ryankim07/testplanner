<?php namespace App\Http\Controllers;

/**
 * Class TicketsController
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
use App\Http\Requests\TicketsFormRequest;
use App\Http\Requests\UserResponseFormRequest;

use App\Facades\Tools;
use App\Facades\Email;

use App\User;
use App\Tickets;
use App\TicketsResponses;
use App\ActivityStream;

use Session;

class TicketsController extends Controller
{
    /**
     * TicketsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('testplanner', [
            'only' => ['build', 'edit']
        ]);
    }

    /**
     * Show the form for creating a new resource
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function build()
    {
        // Get Jira issues
        $jiraIssues = Tools::jiraIssues();

        $ticketsHtml = view('pages/testplanner/partials/tickets', [
            'mode'     => 'create',
            'ticket[]' => [
                'id'         => '',
                'desc'       => '',
                'objective'  => '',
                'test_steps' => ''
            ],
            'addTicketBtnType' => 'btn-custom'
        ])->render();

        return view('pages.testplanner.step_2', [
            'plan' => [
                'mode'          => 'build',
                'tickets_html'  => $ticketsHtml,
                'jira_issues'   => json_encode($jiraIssues)
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function edit()
    {
        // Get tickets session data
        $ticketsData = Session::get('mophie_testplanner.tickets');

        $ticketsHtml = '';
        foreach($ticketsData as $ticket) {
            $ticketsHtml .= view('pages/testplanner/partials/tickets', [
                'mode'             => 'edit',
                'ticket'           => $ticket,
                'addTicketBtnType' => 'btn-primary'
            ])->render();
        }

        // Get Jira issues
        $jiraIssues = Tools::jiraIssues();

        return view('pages.testplanner.step_2', [
            'plan' => [
                'mode'         => 'edit',
                'tickets_html' => $ticketsHtml,
                'jira_issues'  => json_encode($jiraIssues)
            ]
        ]);
    }

    /**
     * Update the specified resource in storage
     *
     * @param TicketsFormRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(TicketsFormRequest $request)
    {
        $tickets = json_decode($request->get('tickets_obj'), true);

        // Save tickets data to session
        Session::put('mophie_testplanner.tickets', $tickets);

        return redirect('plan/review');
    }

    /**
     * Save user's response
     *
     * @param UserResponseFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function save(UserResponseFormRequest $request)
    {
        $planData = json_decode($request->get('plan'), true);
        $tickets  = json_decode($request->get('tickets_obj'), true);
        $planData['tickets_responses'] = $tickets;

        // Save ticket response
        $response = TicketsResponses::saveResponse($planData);

        if (!$response) {
            return redirect()->action('PlansController@respond')
                ->withInput()
                ->withErrors(['message' => config('testplanner.messages.plan.response_error')]);
        } elseif ($response != 'new') {
            // Log activity
            ActivityStream::saveActivityStream($planData, 'ticket-response', $response);

            // Mail all test browsers
            Email::sendEmail('ticket-response', [
                'plan_id'            => $planData['plan_id'],
                'description'        => $planData['description'],
                'tester_id'          => $planData['tester_id'],
                'creator_first_name' => $planData['reporter'],
                'creator_email'      => User::getUserEmail($planData['creator_id']),
                'tester_first_name'  => $planData['assignee'],
                'tester_email'       => User::getUserEmail($planData['tester_id']),
                'response'           => $response
            ]);

            return redirect('dashboard')->with('flash_success', config('testplanner.messages.plan.response_success'));
        }

        return redirect('dashboard');
    }

    /**
     * Store a newly created resource in storage
     *
     * @param TicketsFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function store(TicketsFormRequest $request)
    {
        $tickets = json_decode($request->get('tickets_obj'), true);

        // Save tickets data to session
        Session::put('mophie_testplanner.tickets', $tickets);

        return redirect('tester/build');
    }

    /**
     * Remove tickets from review page
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeTicketAjax(Request $request)
    {
        // Get tickets session data
        $ticketsData = Session::get('mophie_testplanner.tickets');
        $ticketId    = $request->get('ticketId');

        $modifiedData = Tickets::removeTicketFromSession($ticketsData, $ticketId);

        // Save plan data to session
        Session::put('mophie_testplanner.tickets', $modifiedData);

        return response()->json('success');
    }
}