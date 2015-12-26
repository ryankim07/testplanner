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
            /** CUSTOMER **/

            case url() . '/customer/create':
                if (!isset($session['plan'])) {
                    return redirect('plan');
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