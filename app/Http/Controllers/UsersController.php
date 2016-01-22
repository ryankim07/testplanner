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
use App\Http\Requests\RegisterFormRequest;
use App\Http\Requests\UserResponseFormRequest;

use App\User;
use App\Tables;
use App\Role;

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
        list($id, $firstName, $lastName, $email, $active, $userRoles) = explode(':', $request->get('info'));

        $userRoles = explode(',', $userRoles);

        $user = [
            'id'         => $id,
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'email'      => $email,
            'active'     => $active,
        ];

        // Prepare dropdown list of all roles
        $allRoles  = Role::all();

        foreach($allRoles as $eachRole) {
            $rolesOptions[$eachRole->id] = $eachRole->custom_role_name;
        }

        $viewHtml = view('pages.main.user', [
            'mode'                 => 'edit',
            'user'                 => $user,
            'rolesOptions'         => $rolesOptions,
            'rolesSelectedOptions' => count($userRoles) > 0 ? $userRoles : ''
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
        Session::flash('flash_message', config('testplanner.messages.users.update'));

        // Return JSON success message and redirect url
        return response()->json([
            'type'         => 'success',
            'redirect_url' => url('user/all')
        ]);
    }
}