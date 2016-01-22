<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Http\Requests\LoginFormRequest;
use App\Http\Requests\RegisterFormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use PhpSpec\Exception\Exception;

use App\Facades\Tools;

use App\User;
use App\UserRole;
use App\Role;

use Auth;
use Session;

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
                ->withErrors(['message' => config('testplanner.messages.users.account_inactive')]);
        }

        if ($this->auth->attempt($request->only('email', 'password')))
        {
            return redirect()->intended('dashboard');
        }

        return redirect('auth/login')
            ->withInput()
            ->withErrors(['message' => config('testplanner.messages.users.credentials_error')]);
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

        $viewHtml = view('pages.main.user', [
            'mode'                 => 'register',
            'user'                 => '',
            'rolesOptions'         => $rolesOptions,
            'rolesSelectedOptions' => ''
        ])->render();

        return response()->json(["viewBody" => $viewHtml]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param RegisterFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function postRegister(RegisterFormRequest $request)
    {
        $redirect = false;
        $errorMsg = '';

        // Start transaction
        DB::beginTransaction();

        // Register new user
        try {
            $user   = $this->user->where('email', '=', $request->email)->first();
            $userId = isset($user->id) ? $user->id : false;

            // Save user
            if (!$userId) {
                $this->user->first_name = $request->first_name;
                $this->user->last_name  = $request->last_name;
                $this->user->email      = $request->email;
                $this->user->password   = bcrypt($request->password);
                $this->user->active     = 1;
                $this->user->save();

                $userId = $this->user->id;
            }

            // Roles that were selected to be registered
            $selectedRoles = explode(',', $request->get('role'));

            // Find all roles for this user
            $userRoles = $this->user->findOrNew($userId)->roles();

            $existingRoles = [];
            foreach($userRoles->get() as $user) {
                $existingRoles[] = $user->role_id;
            }

            // Identical user with all the roles, throw error
            if (isset($userId) && $userRoles->count() == 3) {
                throw new Exception(config('testplanner.messages.users.identical_user'));
            }

            // Throw error if role already exists
            if (count(array_diff($selectedRoles, $existingRoles)) == 0) {
                throw new Exception(config('testplanner.messages.users.identical_role'));
            }

            // Add user's role
            UserRole::addRoles($userId, $selectedRoles);
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            $redirect = true;
        } catch (QueryException $e) {
            $errorMsg = $e->getErrors();
            $redirect = true;
        } catch (ModelNotFoundException $e) {
            $errorMsg = $e->getErrors();
            $redirect = true;
        }

        // Redirect if errors
        if ($redirect) {
            // Rollback
            DB::rollback();

            // Log specific technical message
            Tools::log($errorMsg, array_except($request->all(), [
                '_token',
                'created_from',
                'created_to',
                'password',
                'password_confirmation'
            ]));

            // Return JSON error response
            return response()->json([
                'type' => 'error',
                'msg'  => config('testplanner.messages.users.new_error')
            ]);
        }

        // Commit all changes
        DB::commit();

        // Flash message so it could be shown once redirected by AJAX call
        Session::flash('flash_message', config('testplanner.messages.users.new'));

        // Return JSON success message and redirect url
        return response()->json([
            'type'         => 'success',
            'redirect_url' => url('user/all')
        ]);
    }
}