<?php namespace App\Listeners;

/**
 * Class AuthLogout
 *
 * Listener
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://tp.nophie.us)
 */

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Session;

class AuthLogout
{
    /**
     * AuthLogout constructor
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  auth.logout  $event
     * @return void
     */
    public function handle()
    {
        Session::forget('mophie_testplanner');
        Session::forget('mophie.user');
        Session::forget('mophie.all_users');
    }
}
