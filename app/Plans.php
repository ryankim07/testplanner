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
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use PhpSpec\Exception\Exception;

use App\Facades\Utils;
use App\Facades\Jira;

use App\Tickets;
use App\Testers;

use Session;

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
        parent::boot();

        static::creating(function($content)
        {
            $content->started_at = date('Y-m-d h:i:s', strtotime($content->started_at));
            $content->expired_at = date('Y-m-d 23:59:59', strtotime($content->expired_at));
        });
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
        $query = DB::table('plans AS p')
            ->join('users AS u', 'u.id', '=', 'p.creator_id')
            ->select('p.*', DB::raw('CONCAT(u.first_name, " ", u.last_name) AS full_name'));

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
            $query->take(5);
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
        $query = DB::table('plans AS p')
            ->join('testers AS t', 'p.id', '=', 't.plan_id')
            ->join('users AS u', 'u.id', '=', 'p.creator_id')
            ->leftJoin('tickets_responses AS tr', 'p.id', '=', 'tr.plan_id')
            ->select('p.*', DB::raw('CONCAT(u.first_name, " ", u.last_name) AS full_name'), 't.browser', 'tr.status AS ticket_response_status')
            ->where('t.user_id', '=', $userId)
            ->where('p.status', '=', 'new')
            ->orWhere('p.status', '=', 'incomplete')
            ->orderBy($sortBy, $order);

        if ($from == 'dashboard') {
            $query->take(5);
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
            $newResults = array();

            foreach ($results['tickets'] as $ticket) {
                $responses = unserialize($ticketsResponses->responses);

                foreach($responses as $response) {
                    if ($ticket['id'] == $response['id']) {
                        $ticketDesc = $ticket['desc'];
                        list($project, $summary) = explode(':', $ticketDesc);

                        if (preg_match('/^ECOM-\d/', $project)) {
                            $descUrl = url(config('testplanner.jira_domain')) . '/browse/' . $project;
                        }
                        $newResults[$ticket['id']] = array(
                            'id'              => $ticket['id'],
                            'desc'            => $ticket['desc'],
                            'description_url' => $descUrl,
                            'objective'       => $ticket['objective'],
                            'test_steps'      => $ticket['test_steps'],
                            'notes_response'  => nl2br($response['notes_response']),
                            'test_status'     => isset($response['test_status']) ? $response['test_status'] : null
                        );
                    }
                }
            }

            unset($results['tickets']);

            $results['created_at']    = Utils::dateAndTimeConverter($results['created_at']);
            $results['updated_at']    = Utils::dateAndTimeConverter($results['updated_at']);
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
        $results = DB::table('plans AS p')
            ->join('testers AS t', 'p.id', '=', 't.plan_id')
            ->join('tickets AS ti', 'p.id', '=', 'ti.plan_id')
            ->select('p.*', 't.user_id AS tester_id', 't.browser', 'ti.tickets')
            ->where('p.id', '=', $planId)
            ->where('t.user_id', '=', $userId)
            ->first();

        $results             = get_object_vars($results);
        $results['reporter'] = User::getUserFirstName($results['creator_id'], 'first_name');
        $results['assignee'] = User::getUserFirstName($results['tester_id'], 'first_name');
        $results['tickets']  = unserialize($results['tickets']);

        return $results;
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

        return true;
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
            $planData['id'] = $planId;

            if (isset($plan->id)) {
                // Save new tickets
                Tickets::create([
                    'plan_id' => $planId,
                    'tickets' => serialize($ticketsData)
                ]);

                // Save new testers
                foreach($testerData as $tester) {
                    Testers::create([
                        'plan_id' => $planId,
                        'user_id' => $tester['id'],
                        'browser' => $tester['browser']
                    ]);

                    // Create object for email
                    $testersWithEmail[] = [
                        'tester_id'  => $tester['id'],
                        'first_name' => $tester['first_name'],
                        'browser'    => $tester['browser'],
                        'email'      => User::getUserEmail($tester['id'])
                    ];
                }
            }
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            $redirect = true;
        } catch (ValidationException $e) {
            $errorMsg = $e->getErrors();
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
            Utils::log($errorMsg, array_merge($planData, $ticketsData, $testerData));

            // Delete session
            Session::forget('mophie_testplanner');

            return redirect()->action('PlansController@build')
                ->withInput()
                ->withErrors(array('message' => config('testplanner.plan_build_error_msg')));
        }

        // Commit all changes
        DB::commit();

        $results = [
            'plan_id' => $plan->id,
            'testers' => $testersWithEmail
        ];

        return $results;
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