<?php namespace App\Http\Controllers;

/**
 * Class ActivityStreamController
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Facades\Tools;

use App\User;
use App\ActivityStream;
use App\ActivityComments;
use App\Tables;

use Auth;

class ActivityStreamController extends Controller
{
    /**
     * DashboardController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        $table = Tables::prepare('order', [
            'activity',
            'created_at'
        ], 'ActivityStreamController@index');

        return view('pages.testplanner.view_all_activities', [
            'activities'      => ActivityStream::paginate(config('testplanner.tables.pagination.activity_stream')),
            'totalActivities' => ActivityStream::count(),
            'columns'         => $table['columns'],
            'columnsLink'     => $table['columns_link'],
            'link'            => ''
        ]);
    }

    /**
     * Create comment in activity stream
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request)
    {
        $results = ActivityComments::saveActivityComment($request->get('as_id'), Auth::user()->id, $request->get('comment'));

        return response()->json([
            "status"      => "success",
            "commentator" => User::getUserFirstName(Auth::user()->id),
            "comment"     => $results->comment,
            "created_at"  => Tools::dateConverter($results->created_at)
        ]);
    }

    public function search()
    {

    }
}