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

use App\Api\UserApi;
use App\Api\TablesApi;

use Session;

class UsersController extends Controller
{
    protected $userApi;

    /**
     * TicketsController constructor.
     */
    public function __construct(UserApi $userApi)
    {
        $this->middleware('auth');
        $this->userApi = $userApi;
    }

    /**
     * Get all users
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        $results = $this->userApi->getAllUsers();

        return view('pages.main.view_all_users', $results);
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
        $results = $this->userApi->displayUser($info);

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
        $results = $this->userApi->updateUser($request);

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