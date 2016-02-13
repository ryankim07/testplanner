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

use App\Api\TablesApi;

use Auth;
use Session;

class PlansApi extends BaseApi
{
    /**
     * @var Plans
     */
    protected $model;

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
     * @var UserApi
     */
    protected $userApi;

    /**
     * @var Tables
     */
    protected $tablesApi;

    /**
     * @var
     */
    protected $authUser;

    /**
     * Plans constructor.
     *
     * @param Plans $plans
     */
    public function __construct(Plans $plans, Tickets $tickets, TicketsResponses $tr,
                                Testers $testers, UserApi $userApi, TablesApi $tablesApi)
    {
        $this->model        = $plans;
        $this->ticketsModel = $tickets;
        $this->trModel      = $tr;
        $this->testersModel = $testers;
        $this->userApi      = $userApi;
        $this->tablesApi    = $tablesApi;
        $this->authUser     = Session::get('mophie.user');
    }

    /**
     * Display plans that were created and assigned to the user
     *
     * @return array
     */
    public function getDashboardLists()
    {
        // Get user's role
        $user         = $this->authUser;
        $allAssigned  = [];
        $allResponses = [];

        // Get assigned plans from others
        $allAssigned = $this->getAllAssigned($user['id'], 'created_at', 'DESC', 'dashboard');
        $results['plans_assigned'] = $allAssigned;

        // Display selected creator of the plan
        if (Tools::checkUserRole($user['roles'], ['administrator'])) {
            $allResponses = $this->getAllResponses($user['id'], 'created_at', 'DESC', 'dashboard');
            $results['admin_created_plans'] = $allResponses;
        }

        return $results;
    }

    /**
     * Display plan
     *
     * @param $planId
     * @param $userApi
     * @param $jiraApi
     * @return array
     */
    public function viewPlan($planId, $userApi, $jiraApi)
    {
        $plan      = $this->model->find($planId);
        $tickets   = unserialize($plan->ticket()->first()->tickets);
        $usersList = $userApi->usersList();

        foreach($plan->testers()->get() as $tester) {
            $responses = $tester->tickets()->where('plan_id', '=', $plan->id)->get();
            $tester->responses = [];

            foreach($responses as $response) {
                $exists = false;
                if (isset($response->id) && $response['status'] != 'new') {
                    $exists = true;
                }

                $thisResponse[$response->browser] = $exists;
                $tester->responses = $thisResponse;
            }

            $results[] = $tester->toArray();
        }

        $testers = $results;

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
        $jiraVersions = $jiraApi->jiraVersions();

        // Get Jira issues
        $jiraIssues = $jiraApi->jiraIssues();

        $results = [
            'plan' => [
                'id'            => $plan->id,
                'description'   => $plan->description,
                'started_at'    => Tools::dateConverter($plan->started_at),
                'expired_at'    => Tools::dateConverter($plan->expired_at),
                'tickets_html'  => $ticketsHtml,
                'users'         => $usersList,
                'testers'       => json_encode($testers),
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
        $testers = $this->model->find($planId)->testers()->get();

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
                'selectorName' => $testerFirstName,
                'image'        => ''
            ])->render();

            // Plan details
            $plan = $this->planRenderer($planId, $testerId);

            // Inner Tabs
            $tabHeaderHtml = '';
            $tabBodyHtml   = '';

            foreach ($browsers as $browser) {
                $responses = isset($plan['responses'][$browser]) ? $plan['responses'][$browser] : [
                    'ticket_resp_id'  => '',
                    'response_status' => '',
                    'tickets'         => []
                ];

                // Browsers header tab
                $tabHeaderHtml .= view('pages/testplanner/partials/response_respond/tab_header', [
                    'selectorId'   => $browser . '-' . $testerId,
                    'selectorName' => $browser,
                    'image'        => '<img src="' . asset("/images/{$browser}.png") . '" alt="' . $browser . '">'
                ])->render();

                // Browsers main body tab
                $tabBodyHtml .= view('pages/testplanner/partials/response_respond/tab_body', [
                    'mode'           => 'responses',
                    'browserName'    => $browser,
                    'paneSelectorId' => $browser . '-' . $testerId,
                    'responses'      => $responses,
                    'plan'           => array_only($plan, [
                        'reporter',
                        'assignee',
                        'started_at',
                        'expired_at',
                        'created_at',
                        'updated_at'
                    ])
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
            'description'     => $plan['description'],
            'usersTabHtml'    => $usersTabHtml,
            'browsersTabHtml' => $browsersTabHtml
        ];

        return $results;
    }

    /**
     * Respond to ticket
     *
     * @param $planId
     * @param $userId
     * @return array
     */
    public function respond($planId, $userId)
    {
        // Get plan details
        $plan = $this->planRenderer($planId, $userId);

        $tabHeaderHtml = '';
        $tabBodyHtml   = '';

        $browsers = array_filter(explode(',', $plan['browsers']));

        foreach ($browsers as $browser) {
            // Render users tab
            $responses = isset($plan['responses'][$browser]) ? $plan['responses'][$browser] : [
                'ticket_resp_id'  => '',
                'response_status' => '',
                'tickets'         => []
            ];

            $tabHeaderHtml .= view('pages/testplanner/partials/response_respond/tab_header', [
                'selectorId'   => $browser,
                'selectorName' => $browser,
                'image'        => '<img src="' . asset("/images/{$browser}.png") . '" alt="' . $browser . '">'
            ]);

            $tabBodyHtml .= view('pages/testplanner/partials/response_respond/tab_body', [
                'mode'           => 'respond',
                'browserName'    => $browser,
                'paneSelectorId' => $browser,
                'responses'      => $responses,
                'plan'           => array_only($plan, [
                    'reporter',
                    'assignee',
                    'started_at',
                    'expired_at',
                    'created_at',
                    'updated_at'
                ])
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
        // If there's no user ID, get current user
        if (!isset($userId)) {
            $userId = $this->authUser['id'];
        }

        // Display selected creator of the plan
        if ($isRoot = Tools::checkUserRole($this->authUser['roles'], ['root'])) {
            $userId = '';
        }

        // Only root can see what all other creators plans
        $dropDownOptions = [];

        if (empty($userId)) {
            // Administrators who created plans
            $admins = $this->userApi->getAllUsersByRole(['root', 'administrator']);

            // Set up dropdown list of all admins
            $dropDownOptions = Tools::getUsersDropdrownOptions($admins, 'admin');
        }

        // Prepare columns to be shown
        $table = $this->tablesApi->prepare('order', [
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
        $table = $this->tablesApi->prepare('order', [
            'description',
            'status',
            'created_at',
            'updated_at',
            'view'
        ], 'PlansController@index');

        $query = $this->getAllPlans($table['sorting']['sortBy'], $table['sorting']['order'], $userId);

        if ($from == 'dashboard') {
            $query = $query->take(config('testplanner.tables.pagination.dashboard'))->get();
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
            ->select('p.*', 'u.first_name', 'u.last_name', 't.browsers')
            ->where('t.user_id', '=', $userId)
            ->orderBy($sortBy, $order)
            ->groupBy('p.id');

        if ($from == 'dashboard') {
            $query= $query->take(config('testplanner.tables.pagination.dashboard'))->get();
        } else {
            $query = $query->paginate(config('testplanner.tables.pagination.lists'));
        }

        // Prepare columns to be shown
        $table = $this->tablesApi->prepare('order', [
            'description',
            'first_name',
            'last_name',
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
     * @return array
     */
    public function planRenderer($planId, $userId)
    {
        // Get plan
        $plan = DB::table('plans AS p')
            ->join('testers AS t', 'p.id', '=', 't.plan_id')
            ->join('tickets AS ti', 'p.id', '=', 'ti.plan_id')
            ->select('p.*', 'p.id AS plan_id', 't.user_id AS tester_id', 't.browsers', 'ti.tickets')
            ->where('p.id', '=', $planId)
            ->where('t.user_id', '=', $userId)
            ->first();

        // Get responses
        $ticketsResponses = $this->trModel->where('plan_id', '=', $planId)
            ->where('tester_id', '=', $userId)
            ->get();

        // Get ticket definitions
        $ticketTexts = unserialize($plan->tickets);

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

                            if (preg_match('/^ECOM-\d/', $ticketDesc)) {
                                list($project, $summary) = explode(':', $ticketDesc);
                                $descUrl = url(config('testplanner.jira.info.domain')) . '/browse/' . $project;
                            } else {
                                $descUrl = '';
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

        $plan->reporter    = Tools::getUserFirstName($plan->creator_id);
        $plan->assignee    = Tools::getUserFirstName($plan->tester_id);
        $plan->browsers    = $plan->browsers;
        $plan->description = $plan->description;

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
     * @param $request
     * @return array|bool
     */
    public function updateBuiltPlanDetails($planId, $request)
    {
        $redirect = false;
        $errorMsg = '';

        // Start transaction
        DB::beginTransaction();

        // Start plan update
        try {
            $plan = $this->model->find($planId);

            $plan->update([
                'description' => $request->get('description'),
                'started_at'  => $request->get('started_at'),
                'expired_at'  => $request->get('expired_at')
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
            Tools::log($errorMsg, $request);

            return false;
        }

        // Commit all changes
        DB::commit();

        $results = [
            'type'        => 'plan',
            'status'      => 'update',
            'id'          => $plan->id,
            'creator_id'  => $plan->creator_id,
            'description' => $plan->description
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
            $plan   = $this->model->create($planData);
            $planId = $plan->id;

            if (isset($plan->id)) {
                // Save new tickets
                $this->ticketsModel->create([
                    'plan_id' => $planId,
                    'tickets' => serialize($ticketsData)
                ]);

                // Save new testers
                $assignedTesters = [];

                foreach($testerData as $tester) {
                    if (count($tester['input-ids']) > 0 && !empty($tester['browsers'])) {
                        $this->testersModel->create([
                            'plan_id' => $planId,
                            'user_id' => $tester['id'],
                            'browsers' => $tester['browsers']
                        ]);

                        $assignedTesters[] = $tester;
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
            Tools::log($errorMsg, array_merge($planData, $ticketsData, $assignedTesters));

            return false;
        }

        // Commit all changes
        DB::commit();

        if (!$planId) {
            return false;
        }

        $planData += [
            'type'    => 'plan',
            'status'  => 'new',
            'plan_id' => $planId,
            'testers' => $assignedTesters
        ];

        return $planData;
    }

    /**
     * Setup additional keys/values when searching all created
     *
     * @param $searchTerms
     * @param $roles
     * @param $adminId
     * @return array|mixed
     */
    public function prepareSearchAllCreated($searchTerms, $roles, $adminId)
    {
        // If user has root privileges, get all the plans that were created.
        // Otherwise just get the plans created with administrator privilege.
        $adminList = [];
        $roleName = '';

        if (in_array('root', $roles)) {
            $userId = 0;
            $roleName = 'root';
        }

        // Display selected creator of the plan
        if (isset($adminId)) {
            $userId = $adminId;
        }

        // Administrators who created plans
        $admins = $this->userApi->getAllUsersByRole(['root', 'administrator']);

        // Set up dropdown list of all admins
        $adminsList = Tools::getUsersDropdrownOptions($admins, 'admin');

        $columns = [
            'description',
            'first_name',
            'last_name',
            'status',
            'created_at',
            'updated_at',
            'edit'
        ];

        $results = $this->tablesApi->searchPlans($searchTerms, $columns);

        $results += [
            'userId'     => $userId,
            'role'       => $roleName,
            'adminsList' => $adminsList
        ];

        return $results;
    }

    /**
     * Check if same plan already exists by Jira build version
     *
     * @param $buildId
     * @return bool
     */
    public function checkPlanJiraBuildVersion($buildId)
    {
        $results = $this->model->where('jira_bvid', '=', $buildId)->first();

        if (isset($results->id)) {
            return false;
        }

        return true;
    }
}