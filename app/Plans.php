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

use App\Facades\Utils;
use App\Facades\Jira;

use App\Tickets;
use App\Testers;

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
            ->select('p.*', 'u.first_name AS creator', 'u.last_name');

        if (!empty($userId)) {
            $query->where('p.creator_id', '=', $userId);
        }

        $query->orderBy($sortBy, $order);

        return $query;
    }

    /**
     * Get all plans created by admin
     *
     * @param $roleId
     * @param $from
     * @return array
     */
    public static function getAdminCreatedPlansResponses($roleId, $sortBy, $order, $from = null)
    {
        $user = UserRole::where('role_id', '=', $roleId)->first();

        $query = DB::table('plans AS p')
            ->join('users AS u', 'u.id', '=', 'p.creator_id')
            ->select('p.*', 'u.id AS user_id', 'u.first_name AS creator')
            ->where('p.creator_id', '=', $user->user_id)
            ->orderBy($sortBy, $order);

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
    public static function getPlansAssignedResponses($sortBy, $order, $from = null)
    {
        $user  = Auth::user();

        $query = DB::table('plans AS p')
            ->join('testers AS t', 'p.id', '=', 't.plan_id')
            ->join('users AS u', 'u.id', '=', 'p.creator_id')
            ->leftJoin('tickets_responses AS tr', 'p.id', '=', 'tr.plan_id')
            ->select('p.*', 't.tester_id', 'u.first_name AS creator', 't.browser', 'tr.status AS ticket_response_status')
            ->where('t.tester_id', '=', $user->id)
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
                        $ticketDesc = $ticket['description'];
                        list($project, $summary) = explode(':', $ticketDesc);

                        if (preg_match('/^ECOM-\d/', $project)) {
                            $descUrl = url(config('testplanner.jira_domain')) . '/browse/' . $project;
                        }
                        $newResults[$ticket['id']] = array(
                            'id'              => $ticket['id'],
                            'description'     => $ticket['description'],
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
            ->select('p.*', 't.tester_id', 't.browser', 'ti.tickets')
            ->where('p.id', '=', $planId)
            ->where('t.tester_id', '=', $userId)
            ->first();

        $results             = get_object_vars($results);
        $results['reporter'] = User::getUserFirstName($results['creator_id'], 'first_name');
        $results['assignee'] = User::getUserFirstName($results['tester_id'], 'first_name');
        $results['tickets']  = unserialize($results['tickets']);

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
        // Start transaction
        DB::beginTransaction();

        // Start plan creation
        try {
            // Save new plan build
            $plan = Plans::create($planData);
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
                        'plan_id'   => $planId,
                        'tester_id' => $tester['id'],
                        'browser'   => $tester['browser']
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
                ->withErrors(array('message' => config('testplanner.plan_build_error')));
        }

        // Commit all changes
        DB::commit();

        return $testersWithEmail;
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