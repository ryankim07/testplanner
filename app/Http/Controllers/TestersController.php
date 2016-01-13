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

use App\Http\Controllers\Controller;
use App\Http\Requests\TestersFormRequest;

use App\User;
use App\Testers;

use Validator;
use Session;
use App;

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
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function build()
    {
        // All user
        $users = User::all();

        return view('pages.testplanner.step_3', [
            'mode'    => 'build',
            'testers' => $users
        ]);
    }

    /**
     * Show the form for editing the specified resource
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function edit()
    {
        // All user
        $users = User::all();

        // Get testers session data
        $testersData = Session::get('mophie_testplanner.testers');

        foreach($testersData as $tester) {
            $results[$tester['id']] = 'tester-' . $tester['id'] . '-' . $tester['browser'];
        }

        return view('pages.testplanner.step_3', [
            'mode'        => 'edit',
            'testersData' => json_encode($results),
            'testers'     => $users
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
        $testers = array_except($request->all(), ['_token', '_method']);

        foreach(array_shift($testers) as $tester) {
            list($id, $firstName, $browser) = explode(',', $tester);

            $browserTesters[] = array(
                'id'         => $id,
                'first_name' => $firstName,
                'browser'    => $browser
            );
        }

        // Save testers data to session
        Session::put('mophie_testplanner.testers', $browserTesters);

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
        $testers = array_except($request->all(), '_token');

        foreach(array_shift($testers) as $tester) {
            list($id, $firstName, $browser) = explode(',', $tester);

            $browserTesters[] = array(
                'id'         => $id,
                'first_name' => $firstName,
                'browser'    => $browser
            );
        }

        // Save testers data to session
        Session::put('mophie_testplanner.testers', $browserTesters);

        return redirect('plan/review');
    }
}