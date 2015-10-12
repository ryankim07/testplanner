<?php namespace App\Http\Controllers;

/**
 * Class MainController
 *
 * Controller
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Mophie H2Pro
 * @copyright  Copyright (c) 2015 mophie (https://lpp.nophie.com)
 */

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

use PhpSpec\Exception\Exception;

use Validator;
use Session;
use Input;
use App;

class MainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('pages.main.home', ['page' => 'home']);
    }
}