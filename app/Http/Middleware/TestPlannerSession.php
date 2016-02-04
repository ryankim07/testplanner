<?php namespace App\Http\Middleware;

/**
 * Class H2proSession
 *
 * Middleware
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://tp.nophie.us)
 */

use Closure;
use App;

class TestPlannerSession
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
	public function handle($request, Closure $next)
	{
        // Force https on all routes
        if (!$request->secure() && App::environment('prod')) {
            return redirect()->secure($request->getRequestUri());
        }

        $session     = $request->session()->get('mophie_testplanner');
        $userSession = $request->session()->get('mophie.user');
        $url         = rawurldecode($request->url());

        switch($url) {
            /** DASHBOARD **/

            case url() . '/dashboard':
                if (isset($session)) {
                    $request->session()->forget('mophie_testplanner');
                }

                if (!$userSession) {
                    $user = $request->user();
                    $userRoles = $user->role()->get();

                    foreach($userRoles as $role) {
                        $roles[$role->id] = $role->name;
                    }

                    $request->session()->put('mophie.user', [
                        'id'         => $user->id,
                        'first_name' => $user->first_name,
                        'last_name'  => $user->last_name,
                        'email'      => $user->email,
                        'active'     => $user->active,
                        'roles'      => $roles
                    ]);
                }

            break;

            /** PLANS **/

            case url() . '/plan/build':
                if (isset($session)) {
                    $request->session()->forget('mophie_testplanner');
                }
            break;

            case url() . '/plan/{plan}':
                if (!isset($session['plan'])) {
                    return redirect('/')
                        ->withInput()
                        ->withErrors(['message' => config('testplanner.messages.plan.session_error')]);
                }
            break;

            case url() . '/plan/edit':
                if (!isset($session['plan'])) {
                    return redirect('/')
                        ->withInput()
                        ->withErrors(['message' => config('testplanner.messages.plan.session_error')]);
                }
            break;

            case url() . '/plan/review':
            case url() . '/plan/save':
                if (!isset($session['plan']) ||
                    !isset($session['tickets']) ||
                    !isset($session['testers'])) {
                    return redirect('/plan/build')->withInput()
                        ->withErrors(['message' => config('testplanner.messages.plan.session_error')]);
                }
            break;

            /** TICKETS **/

            case url() . '/ticket/build':
                if (!isset($session['plan'])) {
                    return redirect('/plan/build');
                }
            break;

            case url() . '/ticket/{ticket}':
                if (!isset($session['ticket'])) {
                    return redirect('/')
                        ->withInput()
                        ->withErrors(['message' => config('testplanner.messages.plan.session_error')]);
                }
            break;

            case url() . '/ticket/edit':
                if (!isset($session['tickets'])) {
                    return redirect('/')
                        ->withInput()
                        ->withErrors(['message' => config('testplanner.messages.plan.session_error')]);
                }
            break;

            /** TESTERS **/

            case url() . '/tester/build':
                if (!isset($session['plan']) ||
                    !isset($session['tickets'])) {
                    return redirect('/plan/build');
                }
            break;

            case url() . '/tester/{tester}':
                if (!isset($session['testers'])) {
                    return redirect('/')
                        ->withInput()
                        ->withErrors(['message' => config('testplanner.messages.plan.session_error')]);
                }
            break;

            case url() . '/tester/edit':
                if (!isset($session['testers'])) {
                    return redirect('/')
                        ->withInput()
                        ->withErrors(['message' => config('testplanner.messages.plan.session_error')]);
                }
            break;

            default:
                if (!isset($session)) {
                    return redirect('/');
                }
            break;
        }

		return $next($request);
	}
}