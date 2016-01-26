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

use App\Helpers\Tools;

use App\Api\ActivityStreamApi,
    App\Api\UserApi;

use Auth;

class ActivityStreamController extends Controller
{
    /**
     * @var Activity Stream Api
     */
    protected $streamApi;

    /**
     * @var User Api
     */
    protected $userApi;

    /**
     * ActivityStreamController constructor
     *
     * @param ActivityStreamApi $streamApi
     */
    public function __construct(ActivityStreamApi $streamApi, UserApi $userApi)
    {
        $this->middleware('auth');
        $this->streamApi = $streamApi;
        $this->userApi   = $userApi;
    }

    /**
     * Display a listing of the resource.
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        $streams = $this->streamApi->displayActivityStream();

        return view('pages.testplanner.view_all_activities', $streams);
    }

    /**
     * Save comment in activity stream
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save(Request $request)
    {
        $asId    = $request->get('as_id');
        $userId  = Auth::user()->id;
        $comment = $request->get('comment');

        $results = $this->streamApi->saveActivityComment($asId, $userId, $comment);

        return response()->json([
            "status"      => "success",
            "commentator" => $this->userApi->getUserFirstName(Auth::user()->id),
            "comment"     => $results->comment,
            "created_at"  => Tools::dateConverter($results->created_at)
        ]);
    }

    public function search()
    {

    }
}