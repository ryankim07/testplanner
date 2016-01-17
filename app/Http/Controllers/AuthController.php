<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Http\Requests\LoginFormRequest;
use App\Http\Requests\RegisterFormRequest;

use App\User;
use App\UserRole;
use App\Role;
use App\Tables;

use Auth;

class AuthController extends Controller
{
    use AuthenticatesAndRegistersUsers;

    /**
     * AuthController constructor.
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
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View|mixed|void
     */
    public function getLogin()
    {
        if (!Auth::guest()) {
            return redirect('dashboard');
        }

        return view('pages.main.login');
    }

    /**
     *  Handle a login request to the application.
     *
     * @param LoginFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function postLogin(LoginFormRequest $request)
    {
        if ($this->auth->validate(['email' => $request->email, 'password' => $request->password, 'active' => 0])) {
            return redirect($this->loginPath())
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['message' => config('testplanner.acct_inactive_msg')]);
        }

        if ($this->auth->attempt($request->only('email', 'password')))
        {
            return redirect()->intended('dashboard');
        }

        return redirect('auth/login')
            ->withInput()
            ->withErrors(['message' => config('testplanner.credentials_problem_msg')]);
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function getLogout()
    {
        $this->auth->logout();

        return redirect('auth/login');
    }

    /**
     * Show the application registration form.
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function getRegister()
    {
        $allRoles  = Role::all();

        foreach($allRoles as $eachRole) {
            $rolesOptions[$eachRole->id] = ucfirst($eachRole->name);
        }

        return view('pages.main.register', [
            'rolesOptions'         => $rolesOptions,
            'rolesSelectedOptions' => ''
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param RegisterFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function postRegister(RegisterFormRequest $request)
    {
        // Create new user
        $user = $this->user->where('email', '=', $request->email)->first();

        if (!isset($user->id)) {
            $this->user->first_name = $request->first_name;
            $this->user->last_name  = $request->last_name;
            $this->user->email      = $request->email;
            $this->user->password   = bcrypt($request->password);
            $this->user->active     = 1;
            $this->user->save();
        }

        // Find all roles for this user
        $roles = $this->user->find($user->id)->roles()->get();

        $roleExists = false;
        foreach($roles as $role) {
            if ($role->role_id == $request->role) {
                $roleExists = true;
                break;
            }
        }

        if ($roleExists) {
            return redirect('admin/auth/register')
                ->with('flash_message', config('testplanner.admin_identical_role_msg'));
        }

        $newRole = UserRole::create([
            'user_id' => $user->id,
            'role_id' => $request->role
        ]);

        return redirect('dashboard')->with('flash_message', config('testplanner.new_user_added_msg'));;
    }
}