<?php namespace App\Http\Controllers\Admin;

/**
 * Class AuthController
 *
 * Admin Controller
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2015 mophie (https://lpp.nophie.com)
 */

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Http\Requests\LoginFormRequest;
use App\Http\Requests\RegisterFormRequest;

use App\User;
use App\RoleUser;

use Auth;

class AuthController extends Controller
{
    use AuthenticatesAndRegistersUsers;

    /**
     * Create a new authentication controller instance.
     *
     * @param Guard $auth
     * @param User $user
     */
    public function __construct(Guard $auth, User $user)
    {
        $this->auth  = $auth;
        $this->user  = $user;

        $this->middleware('auth', ['except' => ['getLogin', 'postLogin', 'getRegister', 'postRegister']]);
    }



    /**
     * Show the application login form.
     *
     * @return Response
     */
    public function getLogin()
    {
        if (!Auth::guest()) {
            return redirect('admin/dashboard');
        }

        return view('pages.main.login', ['formAction' => 'admin.auth.post.login']);
    }

    /**
     * Handle a login request to the application.
     *
     * @param LoginFormRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postLogin(LoginFormRequest $request)
    {
        if ($this->auth->attempt($request->only('email', 'password')))
        {
            return redirect('admin/dashboard');
        }

        return redirect('admin/auth/login')
            ->with('flash_message', config('testplanner.admin_credentials_problem_msg'));
    }

    /**
     * Log the user out of the application.
     *
     * @return Response
     */
    public function getLogout()
    {
        $this->auth->logout();

        return redirect('admin/auth/login');
    }
}