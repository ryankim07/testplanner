<?php namespace App\Http\Middleware;

/**
 * Class H2proSession
 *
 * Middleware
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

use Closure;
use App;

class TestPlannerSession
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
        // Force https on all routes
        if (!$request->secure() && App::environment('prod')) {
            return redirect()->secure($request->getRequestUri());
        }

        $session = $request->session()->get('mophie_testplanner');
        $url     = $request->url();

        switch($url) {
            /** PLANS **/

            case url() . '/plan/build':
                if (isset($session)) {
                    $request->session()->forget('mophie_testplanner');
                }
            break;

            case url() . '/plan/review':
            case url() . '/plan/save':
                if (!isset($session['plan']) ||
                    !isset($session['tickets']) ||
                    !isset($session['testers'])) {
                    return redirect('plan.build');
                }

            /** TICKETS **/

            case url() . '/ticket/build':
                if (!isset($session['plan'])) {
                    return redirect('plan.build');
                }
            break;

            /** TESTERS **/

            case url() . '/tester/build':
                if (!isset($session['plan']) ||
                    !isset($session['tickets'])) {
                    return redirect('plan.build');
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