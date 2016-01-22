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

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Requests\UserResponseFormRequest;

use App\User;
use App\Tables;

use Session;

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
     * Get all users
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        $table = Tables::prepare('order', [
            'first_name',
            'last_name',
            'email',
            'active',
            'role_names',
            'created_at',
            'updated_at',
            'edit'
        ], 'UsersController@view');

        $query = User::getAllUsers($table['sorting']['sortBy'], $table['sorting']['order']);

        return view('pages.main.view_all_users', [
            'users'       => isset($query) ? $query->paginate(config('testplanner.tables.pagination.lists')) : '',
            'totalUsers'  => isset($query) ? User::count() : 0,
            'columns'     => $table['columns'],
            'columnsLink' => $table['columns_link'],
            'link'        => ''
        ]);
    }

    /**
     * View user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function view(Request $request)
    {
        $info    = $request->get('info');
        $results = User::displayUser($info);

        $viewHtml = view('pages.main.user', [
            'mode'                 => 'edit',
            'user'                 => $results['user'],
            'rolesOptions'         => $results['role_options'],
            'rolesSelectedOptions' => $results['user_roles']
        ])->render();

        return response()->json(["viewBody" => $viewHtml]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserResponseFormRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserResponseFormRequest $request)
    {
        $results = User::updateUser($request);

        if (!$results) {
            // Return JSON error response
            return response()->json([
                'type' => 'error',
                'msg'  => config('testplanner.messages.users.update_error')
            ]);
        }

        // Flash message so it could be shown once redirected by AJAX call
        Session::flash('flash_success', config('testplanner.messages.users.update'));

        // Return JSON success message and redirect url
        return response()->json([
            'type'         => 'success',
            'redirect_url' => url('user/all')
        ]);
    }
}