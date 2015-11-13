<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Http\Requests\LoginFormRequest;
use App\Http\Requests\RegisterFormRequest;

use App\User;

use Auth;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

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

        $this->middleware('auth', [
            'except' => [
                'getLogin',
                'postLogin',
                'getRegister',
                'postRegister'
            ]
        ]);
    }

    /**
     * Show the application registration form.
     *
     * @return Response
     */
    public function getRegister()
    {
        return view('pages.main.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param RegisterFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postRegister(RegisterFormRequest $request)
    {
        $this->user->first_name = $request->first_name;
        $this->user->last_name  = $request->last_name;
        $this->user->email      = $request->email;
        $this->user->password   = bcrypt($request->password);
        $this->user->save();

        $this->auth->login($this->user);

        return redirect('auth/login');
    }

    /**
     * Show the application login form.
     *
     * @return Response
     */
    public function getLogin()
    {
        if (!Auth::guest()) {
            return redirect('/dashboard');
        }

        return view('pages.main.login');
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
            return redirect('/dashboard');
        }

        return redirect()->action('Auth\AuthController@getLogin')
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

        return redirect('auth/login');
    }
}