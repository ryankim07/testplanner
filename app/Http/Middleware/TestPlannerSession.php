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

        $tpSession   = $request->session()->get('mophie_testplanner');
        $userSession = $request->session()->get('tp.user');
        $url         = rawurldecode($request->url());

        switch($url) {
            /** DASHBOARD **/

            case url() . '/dashboard':
                if (isset($tpSession)) {
                    $request->session()->forget('mophie_testplanner');
                }

                if (!$userSession) {
                    $user = $request->user();
                    $userRoles = $user->role()->get();

                    foreach($userRoles as $role) {
                        $roles[$role->id] = $role->name;
                    }

                    $request->session()->put('tp.user', [
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
                if (isset($tpSession)) {
                    $request->session()->forget('mophie_testplanner');
                }
            break;

            case url() . '/plan/{plan}':
                if (!isset($tpSession['plan'])) {
                    return redirect('/')
                        ->withInput()
                        ->withErrors(['message' => config('testplanner.messages.plan.session_error')]);
                }
            break;

            case url() . '/plan/edit':
                if (!isset($tpSession['plan'])) {
                    return redirect('/')
                        ->withInput()
                        ->withErrors(['message' => config('testplanner.messages.plan.session_error')]);
                }
            break;

            case url() . '/plan/review':
            case url() . '/plan/save':
                if (!isset($tpSession['plan']) ||
                    !isset($tpSession['tickets']) ||
                    !isset($tpSession['testers'])) {
                    return redirect('/plan/build')->withInput()
                        ->withErrors(['message' => config('testplanner.messages.plan.session_error')]);
                }
            break;

            /** TICKETS **/

            case url() . '/ticket/build':
                if (!isset($tpSession['plan'])) {
                    return redirect('/plan/build');
                }
            break;

            case url() . '/ticket/{ticket}':
                if (!isset($tpSession['ticket'])) {
                    return redirect('/')
                        ->withInput()
                        ->withErrors(['message' => config('testplanner.messages.plan.session_error')]);
                }
            break;

            case url() . '/ticket/edit':
                if (!isset($tpSession['tickets'])) {
                    return redirect('/')
                        ->withInput()
                        ->withErrors(['message' => config('testplanner.messages.plan.session_error')]);
                }
            break;

            /** TESTERS **/

            case url() . '/tester/build':
                if (!isset($tpSession['plan']) ||
                    !isset($tpSession['tickets'])) {
                    return redirect('/plan/build');
                }
            break;

            case url() . '/tester/{tester}':
                if (!isset($tpSession['testers'])) {
                    return redirect('/')
                        ->withInput()
                        ->withErrors(['message' => config('testplanner.messages.plan.session_error')]);
                }
            break;

            case url() . '/tester/edit':
                if (!isset($tpSession['testers'])) {
                    return redirect('/')
                        ->withInput()
                        ->withErrors(['message' => config('testplanner.messages.plan.session_error')]);
                }
            break;

            default:
                if (!isset($tpSession)) {
                    return redirect('/');
                }
            break;
        }

		return $next($request);
	}
}