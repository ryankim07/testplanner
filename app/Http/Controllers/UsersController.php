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

use App\User;
use App\Tables;
use App\Role;
use App\UserRole;

use Validator;

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
        $user      = User::find($id);
        $userRoles = $user->role->all();
        $allRoles  = Role::all();

        foreach($allRoles as $eachRole) {
            $rolesOptions[$eachRole->id] = ucfirst($eachRole->name);

            foreach($userRoles as $eachUserRole) {
                if ($eachUserRole->id == $eachRole->id) {
                    $rolesSelected[] = $eachUserRole->id;
                }
            }
        }

        $viewHtml = view('pages.main.view_user', [
            'mode'                 => 'view',
            'user'                 => $user,
            'rolesOptions'         => $rolesOptions,
            'rolesSelectedOptions' => count($rolesSelected) > 0 ? $rolesSelected : ''
        ])->render();

        return response()->json(["viewBody" => $viewHtml]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $userId = $request->get('user_id');

        // Custom validator
        $validator = Validator::make(array_except($request->all(), '_token'), [
            'current_roles' => 'required',
            'first_name'    => 'required',
            'last_name'     => 'required',
            'email'         => 'required|email',
            'password'      => 'required|confirmed|min:6'
        ], [
            'current_roles.required' => 'Role is required',
            'first_name.required'    => 'First name is required',
            'last_name.required'     => 'Last name is required',
            'email.required'         => 'Email is required',
            'email.email'            => 'Enter correct email address',
            'password.required'      => 'Password is required',
            'password.confirmed'     => 'Password confirmation is required',
            'password.min'           => 'Password must have a length of 6 characters'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'type' => 'error',
                'msg'  => $validator->errors()->all()
            ]);
        }

        // Update user info
        $user = User::find($userId);
        $user->update([
            'first_name' => $request->get('first_name'),
            'last_name'  => $request->get('last_name'),
            'email'      => $request->get('email'),
            'active'     => $request->get('active'),
            'password'   => bcrypt($request->get('password'))
        ]);

        // Remove all existing roles for user
        if (isset($userId)) {
            UserRole::where('user_id', $userId)->delete();
        }

        // Update user roles
        $newRoles = explode(',', $request->get('new_roles'));

        if (count($newRoles) > 0) {
            foreach($newRoles as $key => $value) {
                UserRole::create([
                    'user_id' => $userId,
                    'role_id' => $value
                ]);
            }
        }

        return response()->json([
            'type' => 'success',
            'msg'  => config('testplanner.user_update_success_msg')
        ]);
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
            'role_names',
            'created_at',
            'updated_at'
        ], 'UsersController@view');

        $query = User::getAllUsers($sorting['sortBy'], $sorting['order']);

        return view('pages.main.view_all_users', [
            'users'       => isset($query) ? $query->paginate(config('testplanner.pagination_count')) : '',
            'totalUsers'  => isset($query) ? User::count() : 0,
            'columns'     => $table['columns'],
            'columnsLink' => $table['columns_link'],
            'link'        => ''
        ]);
    }
}