<?php namespace App\Http\Controllers;

/**
 * Class TicketsController
 *
 * Controller
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2015 mophie (https://lpp.nophie.com)
 */

use App\Http\Controllers\Controller;
use App\Http\Requests\TicketsFormRequest;
use PhpSpec\Exception\Exception;

use Validator;
use Session;
use Input;
use App;

class TicketsController extends Controller
{
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
        return view('pages.testplanner.ticket_build');
    }

    /**
     * Show the form for creating a new resource
     *
     * @return \Illuminate\View\View|Redirect
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage
     *
     * @param TicketsFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function store(TicketsFormRequest $request)
    {
        $res     = $request->all();
        $tickets = json_decode($res['tickets-obj'], true);

        // Save case data to session
        Session::put('mophie_testplanner.tickets', $tickets);

        return redirect('tester/build');
    }
}