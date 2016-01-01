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

use Session;

class TicketsController extends Controller
{
    /**
     * Create a new controller instance.
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
        // Get JIRA issues
        $issues = Jira::getAllIssues('ECOM');

        foreach($issues as $issue) {
            $jiraIssues[] = $issue['key'] . ': ' . $issue['summary'];
        }

        return view('pages.testplanner.build_step_2',  ['jiraIssues' => json_encode($jiraIssues)]);
    }

    /**
     * Show the form for editing the specified resource
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        // Get item session data
        $itemData             = Session::get('mophie_h2pro.item');
        $itemData['carriers'] = Utils::getCarriersList();

        return view('pages.registration.item_edit', compact('itemData'));
    }

    /**
     * Update the specified resource in storage
     *
     * @param ItemFormRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($planId, Request $request)
    {
        $plan = Plans::find($planId);
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
        $tickets = json_decode($res['tickets-obj'], true);

        // Save case data to session
        Session::put('mophie_testplanner.tickets', $tickets);

        return redirect('tester/build');
    }
}