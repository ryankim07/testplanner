<?php namespace App\Http\Controllers;

/**
 * Class ActivityStreamController
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

use App\ActivityStream;
use App\ActivityComments;
use App\Tables;

class ActivityStreamController extends Controller
{
    /**
     * DashboardController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

    }

    public function all()
    {
        $table = Tables::prepare('order', [
            'activity',
            'created_at'
        ], 'ActivityStreamController@index');

        return view('pages.testplanner.view_all_activities', [
            'activities'      => ActivityStream::paginate(config('testplanner.pagination_count')),
            'totalActivities' => ActivityStream::count(),
            'columns'         => $table['columns'],
            'columnsLink'     => $table['columns_link'],
            'link'            => ''
        ]);
    }

    public function search()
    {

    }

    /**
     * Create comment in activity stream
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveComment(Request $request)
    {
        $user = Auth::user();

        ActivityComments::saveActivityComment($request->get('id'), $user->id, $request->get('comment'));

        return response()->json(["status" => "success"]);
    }
}