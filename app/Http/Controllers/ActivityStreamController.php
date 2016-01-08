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

    public function all()
    {
        $sorting = Tables::sorting();
        $table   = Tables::prepareTable($sorting['order'], [
            'description',
            'creator',
            'status',
            'created_at',
            'updated_at'
        ], 'ActivityStreamController@index');

        $query = ActivityStream::all();

        return view('pages.testplanner.view_all_activities', [
            'activities'  => isset($query) ? $query->paginate(config('testplanner.pagination_count')) : '',
            'totalPlans'  => isset($query) ? ActivityStream::count() : 0,
            'columns'     => $table['columns'],
            'columnsLink' => $table['columns_link'],
            'link'        => ''
        ]);
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

        ActivityComments::saveActivityComment($res['id'], $user->id, $res['comment']);

        return response()->json(["status" => "success"]);
    }
}