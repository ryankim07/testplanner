<?php namespace App;

/**
 * Class Plans
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

use App\Facades\Utils;

use App\Tasks;
use App\SubTasks;

use Auth;

class Plans extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "plans";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description',
        'creator_id',
        'status'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = array('id');

    /**
     * Model event to change data before saving to database
     */
    public static function boot()
    {
    }

    /**
     * Get all plans created by admin
     *
     * @param $roleId
     * @return array
     */
    public static function getAdminCreatedPlans($roleId)
    {
        $user = UserRole::where('role_id', '=', $roleId)->first();

        $query = DB::table('plans AS p')
            ->join('users AS u', 'u.id', '=', 'p.creator_id')
            ->select('p.id', 'p.description', 'p.status', 'p.created_at', 'u.id AS user_id', 'u.first_name')
            ->where('p.creator_id', '=', $user->user_id)
            ->orderBy('p.created_at', 'desc')
            ->get();

        $plans = array();

        foreach($query as $plan) {
            $allTesters = Testers::getTestersByPlanId($plan->id);

            foreach($allTesters as $tester) {
                $browserTester[$tester->id] = $tester->first_name;
            }

            $plans[] = [
                'id'          => $plan->id,
                'description' => $plan->description,
                'status'      => $plan->status,
                'created_at'  => $plan->created_at,
                'testers'     => $browserTester
            ];
        }

        return $plans;
    }

    /**
     * Get all plans in which a user is part of
     *
     * @return mixed
     */
    public static function getPlansAssigned()
    {
        $user  = Auth::user();

        $query = DB::table('plans AS p')
            ->join('testers AS t', 'p.id', '=', 't.plan_id')
            ->leftJoin('tickets_responses AS tr', 'p.id', '=', 'tr.plan_id')
            ->select('p.*', 't.tester_id', 't.browser', 'tr.status AS ticket_response_status')
            ->where('t.tester_id', '=', $user->id)
            ->where('p.status', '=', 'new')
            ->orWhere('p.status', '=', 'incomplete')
            ->orderBy('p.created_at', 'desc')
            ->get();

        $plans = '';

        foreach($query as $results) {
            $plans[] = get_object_vars($results);
        }

        return $plans;
    }

    /**
     * Get tester plan's response
     *
     * @param $planId
     * @param $userId
     * @return array|mixed
     */
    public static function getPlanResponses($planId, $userId)
    {
        $results          = Plans::renderPlan($planId, $userId);
        $ticketsResponses = TicketsResponses::where('plan_id', '=', $planId)
            ->where('tester_id', '=', $userId)
            ->first();

        $results['ticket_resp_id'] = isset($ticketsResponses->id) ? $ticketsResponses->id : '';

        if (isset($ticketsResponses->id)) {
            $newResults = array();

            foreach ($results['tickets'] as $ticket) {
                $responses = unserialize($ticketsResponses->responses);

                foreach($responses as $response) {
                    if ($ticket['id'] == $response['id']) {
                        $newResults[$ticket['id']] = array(
                            'id'             => $ticket['id'],
                            'description'    => $ticket['description'],
                            'objective'      => $ticket['objective'],
                            'test_steps'     => $ticket['test_steps'],
                            'notes_response' => nl2br($response['notes_response']),
                            'test_status'    => $response['test_status']
                        );
                    }
                }
            }

            unset($results['tickets']);

            $results['created_at'] = Utils::dateAndTimeConverter($ticketsResponses->created_at);
            $results['updated_at'] = Utils::dateAndTimeConverter($ticketsResponses->updated_at);
            $results['tickets']    = $newResults;
        }

        return $results;
    }

    /**
     * Render plan tickets to be responded
     *
     * @param $planId
     * @param $userId
     * @return array
     */
    public static function renderPlan($planId, $userId)
    {
        $results = DB::table('plans AS p')
            ->join('testers AS t', 'p.id', '=', 't.plan_id')
            ->join('tickets AS ti', 'p.id', '=', 'ti.plan_id')
            ->select('p.*', 't.tester_id', 't.browser', 'ti.tickets')
            ->where('p.id', '=', $planId)
            ->where('t.tester_id', '=', $userId)
            ->first();

        $results               = get_object_vars($results);
        $results['reporter']   = User::getUserFirstName($results['creator_id'], 'first_name');
        $results['assignee']   = User::getUserFirstName($results['tester_id'], 'first_name');
        $results['tickets']    = unserialize($results['tickets']);

        return $results;
    }

    /**
     * One plan could have multiple tickets
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany('App\Tickets');
    }
}