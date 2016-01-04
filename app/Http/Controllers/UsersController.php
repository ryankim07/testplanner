<?php namespace App\Http\Controllers;

/**
 * Class UsersController
 *
 * Controller
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

use App\User;
use App\Tables;

class UsersController extends Controller
{
    /**
     * TicketsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * View user
     *
     * @param $id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function view($id)
    {
        $user = User::find($id);

        return view('pages.testplanner.view_user', $user);
    }

    /**
     * Get all users
     * 
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function all()
    {
        $sorting = Tables::sorting();
        $table   = Tables::prepareTable($sorting['order'], [
            'first_name',
            'last_name',
            'email',
            'active',
            'created_at',
            'updated_at'
        ], 'UsersController@view');

        $query = User::getAllUsers($sorting['sortBy'], $sorting['order']);

        return view('pages.testplanner.view_all_users', [
            'users'       => isset($query) ? $query->paginate(config('testplanner.pagination_count')) : '',
            'totalUsers'  => isset($query) ? User::count() : 0,
            'columns'     => $table['columns'],
            'columnsLink' => $table['columns_link'],
            'link'        => ''
        ]);
    }
}