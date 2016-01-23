<?php namespace App;

/**
 * Class Plans
 *
 * Model
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use PhpSpec\Exception\Exception;

use App\Facades\Tools;

use App\Tickets;
use App\Testers;
use App\TicketsResponses;

use PDO;

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
        'status',
        'started_at',
        'expired_at'
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
     * Format to datetime when saving to database
     *
     * @param $value
     */
    public function setStartedAtAttribute($value)
    {
        $this->attributes['started_at'] = date('Y-m-d H:i:s', strtotime($value));
    }

    /**
     * Format to datetime when saving to database
     *
     * @param $value
     */
    public function setExpiredAtAttribute($value)
    {
        $this->attributes['expired_at'] = date('Y-m-d 23:59:59', strtotime($value));
    }

    /**
     * Always capitalize the first name when saving to the database
     *
     * @param $value
     */
    public function setFirstNameAttribute($value) {
        $this->attributes['first_name'] = ucfirst($value);
    }

    /**
     * Get all plans created by a certain administrator
     *
     * @param $sortBy
     * @param $order
     * @param null $userId
     * @return mixed
     */
    public static function getAllPlans($sortBy, $order, $userId = null)
    {
        // Fetch entire collection as array
        DB::connection()->setFetchMode(PDO::FETCH_ASSOC);

        $query = DB::table('plans AS p')
            ->join('users AS u', 'u.id', '=', 'p.creator_id')
            ->select('p.*', 'u.first_name', 'u.last_name');

        if (!empty($userId)) {
            $query->where('p.creator_id', '=', $userId);
        }

        $query->orderBy($sortBy, $order);

        return $query;
    }

    /**
     * Get all plans responses created by admin
     *
     * @param $userId
     * @param $sortBy
     * @param $order
     * @param null $from
     * @return mixed
     */
    public static function getAllResponses($userId, $sortBy, $order, $from = null)
    {
        $query = self::getAllPlans($sortBy, $order, $userId);

        if ($from == 'dashboard') {
            $query->take(config('testplanner.tables.pagination.dashboard'));
        }

        return $query;
    }

    /**
     * Get all plans in which an admin was assigned
     *
     * @param $sortBy
     * @param $order
     * @param null $from
     * @return array
     */
    public static function getAllAssigned($userId, $sortBy, $order, $from = null)
    {
        // Fetch entire collection as array
        DB::connection()->setFetchMode(PDO::FETCH_ASSOC);

        $query = DB::table('plans AS p')
            ->join('testers AS t', 'p.id', '=', 't.plan_id')
            ->join('users AS u', 'u.id', '=', 'p.creator_id')
            ->leftJoin('tickets_responses AS tr', function($join) use ($userId) {
                $join->on('p.id', '=', 'tr.plan_id')
                    ->where('tr.tester_id', '=', $userId);
            })
            ->select('p.*', 'u.first_name', 'u.last_name', 't.browsers', 'tr.status AS ticket_response_status')
            ->where('t.user_id', '=', $userId)
            ->where('p.status', '=', 'new')
            ->orWhere('p.status', '=', 'progress')
            ->orderBy($sortBy, $order);

        if ($from == 'dashboard') {
            $query->take(config('testplanner.tables.pagination.dashboard'));
        }

        return $query;
    }

    /**
     * Get tester plan's response
     *
     * @param $planId
     * @param $userId
     * @return array|mixed
     */
    public static function getTesterPlanResponse($planId, $userId)
    {
        $results          = self::renderPlan($planId, $userId);
        $ticketsResponses = TicketsResponses::where('plan_id', '=', $planId)
            ->where('tester_id', '=', $userId)
            ->first();

        $results['ticket_resp_id'] = isset($ticketsResponses->id) ? $ticketsResponses->id : '';

        if (isset($ticketsResponses->id)) {
            $newResults = [];

            foreach ($results['tickets'] as $ticket) {
                $responses = unserialize($ticketsResponses->responses);

                foreach($responses as $response) {
                    if ($ticket['id'] == $response['id']) {
                        $ticketDesc = $ticket['desc'];
                        list($project, $summary) = explode(':', $ticketDesc);

                        if (preg_match('/^ECOM-\d/', $project)) {
                            $descUrl = url(config('testplanner.jira.info.domain')) . '/browse/' . $project;
                        }
                        $newResults[$ticket['id']] = [
                            'id'              => $ticket['id'],
                            'desc'            => $ticket['desc'],
                            'description_url' => $descUrl,
                            'objective'       => $ticket['objective'],
                            'test_steps'      => $ticket['test_steps'],
                            'notes_response'  => nl2br($response['notes_response']),
                            'test_status'     => isset($response['test_status']) ? $response['test_status'] : null
                        ];
                    }
                }
            }

            unset($results['tickets']);

            $results['created_at']    = Tools::dateAndTimeConverter($results['created_at']);
            $results['updated_at']    = Tools::dateAndTimeConverter($results['updated_at']);
            $results['ticket_status'] = $ticketsResponses->status;
            $results['tickets']       = $newResults;
        } else {
            $results['ticket_status'] = '';
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
        // Fetch entire collection as array
        DB::connection()->setFetchMode(PDO::FETCH_ASSOC);

        $results = DB::table('plans AS p')
            ->join('testers AS t', 'p.id', '=', 't.plan_id')
            ->join('tickets AS ti', 'p.id', '=', 'ti.plan_id')
            ->select('p.*', 't.user_id AS tester_id', 't.browsers', 'ti.tickets')
            ->where('p.id', '=', $planId)
            ->where('t.user_id', '=', $userId)
            ->first();

        $results['reporter'] = User::getUserFirstName($results['creator_id'], 'first_name');
        $results['assignee'] = User::getUserFirstName($results['tester_id'], 'first_name');
        $results['tickets']  = unserialize($results['tickets']);

        return $results;
    }

    /**
     * Display plans that were created and assigned to the user
     *
     * @param $user
     * @param $roles
     */
    public static function getDashboardCreatedAssigned($user, $roles)
    {
        $plans = [];

        // Get assigned plans from others
        $plans['plans_assigned'] = self::getAllAssigned($user->id, 'created_at', 'DESC', 'dashboard')->get();

        // Display administrator created plans
        $allAdmin = [];
        foreach($roles as $role) {
            if ($role->name == "administrator") {
                $adminCreatedPlans = self::getAllResponses($user->id, 'created_at', 'DESC', 'dashboard');

                foreach ($adminCreatedPlans->get() as $adminPlan) {
                    $testers     = self::getTestersByPlanId($adminPlan['id']);
                    $optionsHtml = Tools::dropDownOptionsHtml($testers);
                    $adminPlan['testers'] = $optionsHtml;
                    $allAdmin[]           = $adminPlan;
                }

                $plans['admin_created_plans'] = $allAdmin;
                break;
            }
        }

        return $plans;
    }

    /**
     * Update built plan details
     *
     * @param $planId
     * @param $request
     * @return bool
     */
    public static function updateBuiltPlanDetails($planId, $request)
    {
        $plan = self::find($planId);
        $plan->update([
            'description' => $request->get('description'),
            'started_at'  => $request->get('started_at'),
            'expired_at'  => $request->get('expired_at')
        ]);

        $results = [
            'id'          => $plan->id,
            'description' => $plan->description,
            'creator_id'  => $plan->creator_id,
            'status'      => $plan->status
        ];

        return $results;
    }

    /**
     * Save new created plan
     *
     * @param $planData
     * @param $ticketsData
     * @param $testerData
     * @return $this|array
     */
    public static function savePlan($planData, $ticketsData, $testerData)
    {
        $redirect = false;
        $errorMsg = '';

        // Start transaction
        DB::beginTransaction();

        // Start plan creation
        try {
            // Save new plan build
            $plan = self::create($planData);
            $planId = $plan->id;
            $planData['plan_id'] = $planId;

            if (isset($plan->id)) {
                // Save new tickets
                Tickets::create([
                    'plan_id' => $planId,
                    'tickets' => serialize($ticketsData)
                ]);

                // Save new testers
                foreach($testerData as $tester) {
                    Testers::create([
                        'plan_id'  => $planId,
                        'user_id'  => $tester['id'],
                        'browsers' => $tester['browsers']
                    ]);
                }
            }
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

            // Log to system
            Tools::log($errorMsg, array_merge($planData, $ticketsData, $testerData));

            return false;
        }

        // Commit all changes
        DB::commit();

        return $plan->id;
    }

    /**
     * Get testers by plan ID
     *
     * @param $id
     * @return array
     */
    public static function getTestersByPlanId($id)
    {
        return Plans::find($id)->testers()->get();
    }

    /**
     * One plan could have multiple tickets
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany('App\Tickets', 'plan_id', 'id');
    }

    /**
     * One plan could have multiple testers
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function testers()
    {
        return $this->hasMany('App\Testers', 'plan_id', 'id');
    }
}