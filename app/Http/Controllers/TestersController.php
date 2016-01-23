<?php namespace App\Http\Controllers;

/**
 * Class Tester
 *
 * Controller
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

use App\Http\Requests\TestersFormRequest;

use App\User;

use Session;

class TestersController extends Controller
{
    /**
     * TestersController constructor.
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
        return view('pages.testplanner.step_3', [
            'mode'  => 'build',
            'users' => $users = User::all()
        ]);
    }

    /**
     * Show the form for editing the specified resource
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function edit()
    {
        return view('pages.testplanner.step_3', [
            'mode'    => 'edit',
            'users'   => Session::get('mophie_testplanner.testers.users'),
            'testers' => json_encode(Session::get('mophie_testplanner.testers.testers'))
        ]);
    }

    /**
     * Update the specified resource in storage
     *
     * @param TestersFormRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(TestersFormRequest $request)
    {
        // Save data to session
        Session::put('mophie_testplanner.testers', [
            'users'   => User::all(),
            'testers' => json_decode($request->get('browser_testers'), true)
        ]);

        return redirect('plan/review');
    }

    /**
     * Store a newly created resource in storage
     *
     * @param TestersFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function store(TestersFormRequest $request)
    {
        // Save data to session
        Session::put('mophie_testplanner.testers', [
            'users'   => User::all(),
            'testers' => json_decode($request->get('browser_testers'), true)
        ]);

        return redirect('plan/review');
    }
}