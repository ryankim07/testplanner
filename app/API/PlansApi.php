<?php namespace App\Api;

/**
 * Class PlansApi
 *
 * Custom Model
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://tp.nophie.us)
 */

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use PhpSpec\Exception\Exception;
use App\Api\Abstracts\BaseApi;

use App\Facades\Tools;

use App\Models\Plans,
    App\Models\User,
    App\Models\Tickets,
    App\Models\TicketsResponses,
    App\Models\Testers;

use App\Api\TablesApi,
    App\Api\UserApi,
    App\Api\TicketsResponsesApi,
    App\Api\JiraApi;

use Auth;

class PlansApi extends BaseApi
{
    /**
     * @var Plans
     */
    protected $model;

    /**
     * @var User
     */
    protected $userModel;

    /**
     * @var TicketsResponses
     */
    protected $trModel;

    /**
     * @var Tickets
     */
    protected $ticketsModel;

    /**
     * @var Testers
     */
    protected $testersModel;

    /**
     * @var \App\Api\UserApi
     */
    protected $userApi;

    /**
     * @var \App\Api\TablesApi
     */
    protected $tableApi;

    /**
     * @var
     */
    protected $authUser;

    /**
     * @var JiraApi
     */
    protected $jiraApi;

    /**
     * Plans constructor.
     *
     * @param Plans $plans
     */
    public function __construct(Plans $plans, User $user, Tickets $tickets, TicketsResponses $tr,
                                Testers $testers, UserApi $userApi, TablesApi $tablesApi,
                                JiraApi $jiraApi)
    {
        $this->model        = $plans;
        $this->userModel    = $user;
        $this->ticketsModel = $tickets;
        $this->trModel      = $tr;
        $this->testersModel = $testers;
        $this->userApi      = $userApi;
        $this->tableApi     = $tablesApi;
        $this->jiraApi      = $jiraApi;
        $this->authUser     = Auth::user();
    }

    /**
     * Display plans that were created and assigned to the user
     *
     * @return array
     */
    public function getDashboardCreatedAssigned()
    {
        // Get user's role
        $user = $this->authUser;

        // Get assigned plans from others
        $allAssigned = $this->getAllAssigned($user->id, 'created_at', 'DESC', 'dashboard');

        // Display selected creator of the plan
        if ($user->hasRole(['administrator'])) {
            $allResponses = $this->getAllResponses($user->id, 'created_at', 'DESC', 'dashboard');
        }

        $results = [
            'plans_assigned'      => $allAssigned,
            'admin_created_plans' => $allResponses
        ];

        return $results;
    }

    /**
     * Display plan
     *
     * @param $id
     * @return array
     */
    public function viewPlan($id)
    {
        $plan    = $this->model->find($id);
        $tickets = unserialize($plan->tickets()->first()->tickets);

        // Render tickets
        $ticketsHtml = '';
        foreach($tickets as $ticket) {
            $ticketsHtml .= view('pages/testplanner/partials/tickets', [
                'mode'             => 'edit',
                'ticket'           => $ticket,
                'addTicketBtnType' => 'btn-custom'
            ])->render();
        }

        // Get Jira versions
        $jiraVersions = $this->jiraApi->jiraVersions();

        // Get Jira issues
        $jiraIssues = $this->jiraApi->jiraIssues();

        $results = [
            'plan' => [
                'id'            => $plan->id,
                'description'   => $plan->description,
                'started_at'    => Tools::dateConverter($plan->started_at),
                'expired_at'    => Tools::dateConverter($plan->expired_at),
                'tickets_html'  => $ticketsHtml,
                'users'         => $this->usersApi->usersList(),
                'testers'       => json_encode($plan->testers()->get()->toArray()),
                'jira_versions' => json_encode($jiraVersions),
                'jira_issues'   => json_encode($jiraIssues)
            ]
        ];

        return $results;
    }

    /**
     * View responses of a plan
     *
     * @param $planId
     * @return array
     */
    public function responses($planId)
    {
        // Plan details
        $testers = $this->getTestersByPlanId($planId);

        $usersTabHtml    = '';
        $browsersTabHtml = '';
        $totalResponses = 0;

        foreach ($testers as $tester) {
            $testerId        = $tester->user_id;
            $testerFirstName = $tester->user_first_name;
            $browsers        = explode(',', $tester->browsers);

            // Render users side tab
            $usersTabHtml .= view('pages/testplanner/partials/response_respond/tab_header', [
                'selectorId'   => $testerFirstName . '-' . $testerId,
                'selectorName' => $testerFirstName
            ])->render();

            // Plan details
            $plan = $this->planRenderer($planId, $testerId);

            // Inner Tabs
            $tabHeaderHtml = '';
            $tabBodyHtml   = '';

            foreach ($browsers as $browser) {
                // Browsers header tab
                $tabHeaderHtml .= view('pages/testplanner/partials/response_respond/tab_header', [
                    'selectorId' => $browser . '-' . $testerId,
                    'selectorName' => $browser
                ])->render();

                // Browsers main body tab
                $tabBodyHtml .= view('pages/testplanner/partials/response_respond/tab_body', [
                    'mode' => 'responses',
                    'browserName' => $browser,
                    'paneSelectorId' => $browser . '-' . $testerId,
                    'plan' => $plan
                ])->render();
            }

            // Users main body tab
            $browsersTabHtml .= view('pages/testplanner/partials/response_respond/browsers_tab_body', [
                'paneSelectorId' => $testerFirstName . '-' . $testerId,
                'tabHeaderHtml'  => $tabHeaderHtml,
                'tabBodyHtml'    => $tabBodyHtml,
                'plan'           => $plan
            ])->render();
        }

        $results = [
            'plan' => ['description' => ''],
            'usersTabHtml'    => $usersTabHtml,
            'browsersTabHtml' => $browsersTabHtml
        ];

        return $results;
    }

    public function respond($planId, $userId)
    {
        // Get plan details
        $plan = $this->planRenderer($planId, $userId);

        $tabHeaderHtml = '';
        $tabBodyHtml   = '';

        $browsers = array_filter(explode(',', $plan['browsers']));

        foreach ($browsers as $browser) {
            // Render users tab
            $tabHeaderHtml .= view('pages/testplanner/partials/response_respond/tab_header', [
                'selectorId' => $browser,
                'selectorName' => $browser
            ]);

            $tabBodyHtml .= view('pages/testplanner/partials/response_respond/tab_body', [
                'mode' => 'respond',
                'browserName' => $browser,
                'paneSelectorId' => $browser,
                'plan' => $plan
            ])->render();
        }

        $results = [
            'plan'          => $plan,
            'tabHeaderHtml' => $tabHeaderHtml,
            'tabBodyHtml'   => $tabBodyHtml
        ];

        return $results;
    }

    /**
     * Get all created plans
     *
     * @param $userId
     * @return array
     */
    public function getAllCreated($userId)
    {
        // Display selected creator of the plan
        if ($isRoot = $this->authUser->hasRole(['root'])) {
            $userId = '';
        }

        // Administrators who created plans
        $dropDownOptions = [];
        $dropDownOptions = $this->userApi->getUsersDropdrownOptions();

        // Prepare columns to be shown
        $table = $this->tableApi->prepare('order', [
            'description',
            'first_name',
            'last_name',
            'status',
            'created_at',
            'updated_at',
            'edit'
        ], 'PlansController@index');

        $query = $this->getAllPlans($table['sorting']['sortBy'], $table['sorting']['order'], $userId);
        $plans = $query->paginate(config('testplanner.tables.pagination.lists'));

        $results = [
            'userId'        => $userId,
            'role'          => $isRoot ? 'root' : '',
            'plans'         => $plans,
            'columns'       => $table['columns'],
            'columnsLink'   => $table['columns_link'],
            'adminsList'    => $dropDownOptions
        ];

        return $results;
    }

    /**
     * Get all plans responses created by admin
     *
     * @param $userId
     * @param null $from
     * @return array
     */
    public function getAllResponses($userId, $sortBy, $order, $from = null)
    {
        // Prepare columns to be shown
        $table = $this->tableApi->prepare('order', [
            'description',
            'status',
            'created_at',
            'updated_at',
            'view'
        ], 'PlansController@index');

        $query = $this->getAllPlans($table['sorting']['sortBy'], $table['sorting']['order'], $userId);

        if ($from == 'dashboard') {
            $query = $query->take(config('testplanner.tables.pagination.dashboard'));
        } else {
            $query = $query->paginate(config('testplanner.tables.pagination.lists'));
        }

        $results = [
            'plans'       => $query,
            'columns'     => $table['columns'],
            'columnsLink' => $table['columns_link']
        ];

        return $results;
    }

    /**
     * Get all plans in which an admin was assigned
     *
     * @param $sortBy
     * @param $order
     * @param null $from
     * @return array
     */
    public function getAllAssigned($userId, $sortBy, $order, $from = null)
    {
        $query = $this->basePlansUsersQuery();
        $query->join('testers AS t', 'p.id', '=', 't.plan_id')
            ->leftJoin('tickets_responses AS tr', function($join) use ($userId) {
                $join->on('p.id', '=', 'tr.plan_id')
                    ->where('tr.tester_id', '=', $userId);
            })
            ->select('p.*', 'u.first_name', 'u.last_name', 't.browsers', 'tr.status AS ticket_response_status')
            ->where('t.user_id', '=', $userId)
            ->where('p.status', '=', 'new')
            ->orWhere('p.status', '=', 'progress')
            ->orderBy($sortBy, $order)
            ->groupBy('p.id');

        if ($from == 'dashboard') {
            $query->take(config('testplanner.tables.pagination.dashboard'));
        } else {
            $query = $query->paginate(config('testplanner.tables.pagination.lists'));
        }

        // Prepare columns to be shown
        $table = $this->tableApi->prepare('order', [
            'description',
            'first_name',
            'last_name',
            'status',
            'created_at',
            'updated_at',
            'respond'
        ], 'PlansController@index');

        $results = [
            'plans'         => $query,
            'columns'       => $table['columns'],
            'columnsLink'   => $table['columns_link']
        ];

        return $results;
    }

    /**
     * Get tester plan's response
     *
     * @param $planId
     * @param $userId
     * @return array|mixed
     */
    public function planRenderer($planId, $userId)
    {
        // Get plan
        $plan = DB::table('plans AS p')
            ->join('testers AS t', 'p.id', '=', 't.plan_id')
            ->join('tickets AS ti', 'p.id', '=', 'ti.plan_id')
            ->select('p.*', 't.user_id AS tester_id', 't.browsers', 'ti.tickets')
            ->where('p.id', '=', $planId)
            ->where('t.user_id', '=', $userId)
            ->first();

        $ticketTexts = unserialize($plan->tickets);

        // Get responses
        $ticketsResponses = $this->trModel->where('plan_id', '=', $planId)
            ->where('tester_id', '=', $userId)
            ->get();

        // If there are no responses, create a blank object
        if ($ticketsResponses->count() == 0) {
            $browsers = explode(',', $plan->browsers);

            foreach($browsers as $browser) {
                $results[$browser] = [
                    'ticket_resp_id'  => '',
                    'response_status' => '',
                    'tickets'         => $ticketTexts
                ];
            }

            $plan->responses = $results;
        } else {
            foreach($ticketsResponses as $row) {
                $responseTickets = [];

                foreach ($ticketTexts as $ticketText) {
                    $responses = unserialize($row->responses);

                    foreach ($responses as $response) {
                        $ticketTextId = $ticketText['id'];
                        if ($ticketTextId == $response['id']) {
                            $ticketDesc = $ticketText['desc'];
                            list($project, $summary) = explode(':', $ticketDesc);

                            if (preg_match('/^ECOM-\d/', $project)) {
                                $descUrl = url(config('testplanner.jira.info.domain')) . '/browse/' . $project;
                            }

                            $responseTickets[$ticketTextId] = [
                                'id'              => $ticketTextId,
                                'desc'            => $ticketDesc,
                                'description_url' => $descUrl,
                                'objective'       => $ticketText['objective'],
                                'test_steps'      => $ticketText['test_steps'],
                                'notes_response'  => nl2br($response['notes_response']),
                                'test_status'     => isset($response['test_status']) ? $response['test_status'] : null
                            ];
                        }
                    }
                }

                $results[$row->browser] = [
                    'ticket_resp_id'  => $row->id,
                    'response_status' => $row->status,
                    'tickets'         => $responseTickets
                ];
            }

            $plan->responses = $results;
        }

        $plan->reporter       = Tools::getUserFirstName($plan->creator_id);
        $plan->assignee       = Tools::getUserFirstName($plan->tester_id);
        $plan->browsers       = $plan->browsers;

        return get_object_vars($plan);
    }

    /**
     * Get testers by plan ID
     *
     * @param $id
     * @return array
     */
    public function getTestersByPlanId($id)
    {
        return $this->model->find($id)->testers()->get();
    }

    /**
     * Update built plan details
     *
     * @param $planId
     * @param $planData
     * @return bool
     */
    public function updateBuiltPlanDetails($planId, $planData)
    {
        $redirect = false;
        $errorMsg = '';

        // Start transaction
        DB::beginTransaction();

        // Start plan update
        try {
            $plan = self::find($planId);

            $plan->update([
                'description' => $planData->get('description'),
                'started_at'  => $planData->get('started_at'),
                'expired_at'  => $planData->get('expired_at')
            ]);
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
            Tools::log($errorMsg, $planData);

            return false;
        }

        // Commit all changes
        DB::commit();

        $results = [
            'plan_id'     => $plan->id,
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
    public function savePlan($planData, $ticketsData, $testerData)
    {
        $redirect = false;
        $errorMsg = '';

        // Start transaction
        DB::beginTransaction();

        // Start plan creation
        try {
            // Save new plan build
            $plan = $this->model->create($planData);
            $planId = $plan->id;

            if (isset($plan->id)) {
                // Save new tickets
                $this->ticketsModel->create([
                    'plan_id' => $planId,
                    'tickets' => serialize($ticketsData)
                ]);

                // Save new testers
                foreach($testerData as $tester) {
                    if (!empty($tester['browsers'])) {
                        $this->testersModel->create([
                            'plan_id' => $planId,
                            'user_id' => $tester['id'],
                            'browsers' => $tester['browsers']
                        ]);
                    }
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
}