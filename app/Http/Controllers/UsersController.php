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
                    $selected[] = $eachUserRole->id;
                }
            }
        }

        $viewHtml = view('pages.main.view_user', [
            'mode'                 => 'view',
            'user'                 => $user,
            'rolesOptions'         => $rolesOptions,
            'rolesSelectedOptions' => $selected
        ])->render();

        return response()->json(["viewBody"  => $viewHtml]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $userId
     * @param RegisterFormRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($userId, RegisterFormRequest $request)
    {
        $res  = array_except($request->all(), '_token');
        $user = User::find($userId);
        $user->update([
            'first_name' => $request->get('first_name'),
            'last_name'  => $request->get('last_name'),
            'email'      => $request->get('email'),
            'active'     => $request->get('active'),
        ]);

        return redirect()->action('UsersController@all')
            ->with('flash_message', config('testplanner.user_update_success_msg'));
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