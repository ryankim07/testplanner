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

use App\TicketsResponses;
use App\Testers;

use Auth;

class Dashboard extends Model
{
    /**
     * Display all plans
     */
    public static function getAllPlans()
    {
    }

    /**
     * Get all admin plans
     *
     * @return mixed
     */
    public static function getAdminPlans()
    {
        $user     = Auth::user();
        $query = DB::table('plans AS p')
            ->join('testers AS t', 'p.id', '=', 't.plan_id')
            ->join('users AS u', 'u.id', '=', 't.tester_id')
            ->select('p.id', 'p.description', 'p.status', 'p.created_at', 'u.id AS user_id', 'u.first_name')
            ->where('p.creator_id', '=', $user->id)
            ->get();

        foreach($query as $plan) {
            $allTesters = Testers::getTestersByPlanId($plan->id);

            foreach($allTesters as $tester) {
                $browserTester[$tester->id] = $tester->first_name;
            }

            $results[] = [
                'plan_id'     => $plan->id,
                'description' => $plan->description,
                'status'      => $plan->status,
                'created_at'  => $plan->created_at,
                'testers'     => $browserTester
            ];
        }

        return $results;
    }

    /**
     * Get all tester plans
     *
     * @return mixed
     */
    public static function getTesterPlans()
    {
        $user  = Auth::user();
        $plans = DB::table('plans AS p')
            ->join('testers AS t', 'p.id', '=', 't.plan_id')
            ->select('p.*', 't.browser')
            ->where('t.tester_id', '=', $user->id)
            ->where('p.status', '=', 'new')
            ->orWhere('p.status', '=', 'incomplete')
            ->get();

        return $plans;
    }

    /**
     * Get tester plan's response
     *
     * @param $planId
     * @param $userId
     */
    public static function getTesterResponse($planId, $userId)
    {
        $query = DB::table('plans AS p')
            ->join('tickets_reponses AS tr', 'p.id', '=', 'tr.plan_id')
            ->join('users AS u', 'tr.tester_id', '=', 'u.id')
            ->select('u.first_name', 'tr.status', 'tr.tickets')
            ->where('tr.plan_id', '=', $planId)
            ->where('tr.tester_id', '=', $userId)
            ->first();

        $tickets = unserialize($query->tickets);
    }
}