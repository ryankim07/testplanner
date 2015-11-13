<?php namespace App;

/**
 * Class Dashboard
 *
 * Model
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2015 mophie (https://lpp.nophie.com)
 */

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\PlansTrack;
use App\TicketsResponses;

use Auth;

class Dashboard extends Model
{
    public static function displayPlanStatus()
    {
        $user = Auth::user();

        $plans = PlansTrack::where('user_id', $user->id)->get();

        return $plans;
    }

    public static function displayTicketStatus()
    {
        $user = Auth::user();

        $tickets = DB::table('plans AS p')
            ->join('tickets_responses AS tr', 'p.id', '=', 'tr.plan_id')
            ->select('p.id', 'p.description', 'tr.status', 'tr.tickets')
            ->where('tr.user_id', '=', $user->id)
            ->get();

        return $tickets;
    }
}