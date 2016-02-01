<?php namespace App\Http\Controllers;

/**
 * Class TicketsController
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
use App\Http\Requests\TicketsFormRequest;
use App\Http\Requests\UserResponseFormRequest;

use App\Facades\Tools;
use App\Helpers\Email;

use App\Api\TicketsApi,
    App\Api\TicketsResponsesApi,
    App\Api\JiraApi;

use Session;

class TicketsController extends Controller
{
    /**
     * @var JiraApi
     */
    protected $jiraApi;


    /**
     * TicketsController constructor.
     */
    public function __construct(JiraApi $jiraApi)
    {
        $this->middleware('auth');
        $this->middleware('testplanner', ['only' => ['build', 'edit']]);
        $this->jiraApi = $jiraApi;
    }

    /**
     * Show the form for creating a new resource
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function build()
    {
        // Grab Jira build version ID
        $buildVersionId = Session::get('mophie_testplanner.plan.build_version_id');

        // Get Jira issues
        $jiraIssues  = $this->jiraApi->jiraIssuesByVersion($buildVersionId);
        $ticketsHtml = '';

        foreach($jiraIssues['specificIssues'] as $issue) {
            $ticketsHtml .= view('pages/testplanner/partials/tickets', [
                'mode'   => 'custom',
                'ticket' => ['desc' => $issue]
            ])->render();
        }

        return view('pages.testplanner.step_2', [
            'plan' => [
                'mode'         => 'build',
                'tickets_html' => $ticketsHtml,
                'jira_issues'  => json_encode($jiraIssues['allIssues']),
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
        // Get from session data
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
        $jiraIssues = $this->jiraApi->jiraIssues();

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
        // Save data to session
        Session::put('mophie_testplanner.tickets', json_decode($request->get('tickets_obj'), true));

        return redirect('plan/review');
    }

    /**
     * Store a newly created resource in storage
     *
     * @param TicketsFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function store(TicketsFormRequest $request)
    {
        // Save data to session
        Session::put('mophie_testplanner.tickets', json_decode($request->get('tickets_obj'), true));

        return redirect('tester/build');
    }

    /**
     * Save user's response
     *
     * @param UserResponseFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function save(Request $request, TicketsResponsesApi $trApi)
    {
        $planData = json_decode($request->get('plan'), true);
        $tickets  = json_decode($request->get('tickets_obj'), true);
        $planData += ['tickets_responses' => $tickets];

        // Save ticket response
        $response = $trApi->saveResponse($planData);

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
                'creator_email'      => Tools::getUserEmail($planData['creator_id']),
                'tester_first_name'  => $planData['assignee'],
                'tester_email'       => Tools::getUserEmail($planData['tester_id']),
                'response'           => $response
            ]);

            return redirect('dashboard')->with('flash_success', config('testplanner.messages.plan.response_success'));
        }

        return redirect('dashboard');
    }

    /**
     * Remove tickets from review page
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeTicketAjax(Request $request, TicketsApi $ticketsApi)
    {
        // Get tickets session data
        $ticketsData = Session::get('mophie_testplanner.tickets');
        $ticketId    = $request->get('ticketId');

        $modifiedData = $ticketsApi->removeTicketFromSession($ticketsData, $ticketId);

        // Save plan data to session
        Session::put('mophie_testplanner.tickets', $modifiedData);

        return response()->json('success');
    }
}