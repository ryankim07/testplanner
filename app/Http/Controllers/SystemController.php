<?php namespace App\Http\Controllers;

/**
 * Class SystemController
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

use App\Facades\Utils;

use App\User;
use App\ActivityStream;
use App\ActivityComments;
use App\Tables;

use Auth;

class SystemController extends Controller
{
    /**
     * DashboardController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {

    }
}