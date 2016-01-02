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

use App\Http\Controllers\Controller;
use App\Http\Requests\TicketsFormRequest;
use PhpSpec\Exception\Exception;

use App\Facades\Jira;

use App\Tickets;

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
        $jiraIssues = $this->_Jira();

        return view('pages.testplanner.step_2', [
            'mode'       => 'build',
            'jiraIssues' => json_encode($jiraIssues)
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
        $ticketsData = Session::get('mophie_testplanner.tester');

        // Get Jira issues
        $jiraIssues    = $this->_Jira();

        return view('pages.testplanner.step_2', [
            'mode'        => 'edit',
            'ticketsData' => $ticketsData,
            'jiraIssues'  => json_encode($jiraIssues)
        ]);
    }

    /**
     * Update the specified resource in storage
     *
     * @param $planId
     * @param Request $request
     */
    public function update($planId, Request $request)
    {
        $plan = Tickets::find($planId);
        $plan->update(['description' => $request->get('description')]);
    }

    /**
     * Store a newly created resource in storage
     *
     * @param TicketsFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function store(TicketsFormRequest $request)
    {
        $res     = array_except($request->all(), '_token');
        $tickets = json_decode($res['tickets_obj'], true);

        // Save case data to session
        Session::put('mophie_testplanner.tickets', $tickets);

        return redirect('tester/build');
    }

    /**
     * Use Jira API
     *
     * @return array
     */
    private function _Jira()
    {
        // Get JIRA issues
        $results = Jira::getAllIssues('ECOM');

        foreach($results as $issue) {
            $issues[] = $issue['key'] . ': ' . $issue['summary'];
        }

        return $issues;
    }
}