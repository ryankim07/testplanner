<?php namespace App\Http\Middleware;

/**
 * Class CheckRole
 *
 * Middleware
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        // Get the required roles from the route
        $roles = $this->getRequiredRoleForRoute($request->route());

        // Check if a role is required for the route, and
        // if so, ensure that the user has that role.
        $user = $request->user();

        if (isset($user)) {
            return $next($request);
        }

        return redirect('dashboard')->with('flash_success', config('testplanner.messages.users.unauthorized'));
    }

    /**
     * Get role
     *
     * @param $route
     * @return null
     */
    private function getRequiredRoleForRoute($route)
    {
        $actions = $route->getAction();

        return isset($actions['roles']) ? $actions['roles'] : null;
    }
}