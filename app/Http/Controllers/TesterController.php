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
use App\Http\Requests\TesterFormRequest;

use PhpSpec\Exception\Exception;

use App\User;

use Validator;
use Session;
use Input;
use App;

class TesterController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('testplanner', [
            'only' => ['build']
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
        $users = User::all();
        return view('pages.testplanner.plan_build_step_3', ['users' => $users]);
    }

    /**
     * Store a newly created resource in storage
     *
     * @param TesterFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function store(TesterFormRequest $request)
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