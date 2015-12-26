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
use App\Facades\Grid;

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
            ->select('p.*', 'u.first_name', 'u.last_name');

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
            ->select('p.id', 'p.description', 'p.creator_id', 'p.status', 'p.created_at', 'u.id AS user_id', 'u.first_name')
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
                        $newResults[$ticket['id']] = array(
                            'id'             => $ticket['id'],
                            'description'    => $ticket['description'],
                            'objective'      => $ticket['objective'],
                            'test_steps'     => $ticket['test_steps'],
                            'notes_response' => nl2br($response['notes_response']),
                            'test_status'    => isset($response['test_status']) ? $response['test_status'] : null
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

        $results             = get_object_vars($results);
        $results['reporter'] = User::getUserFirstName($results['creator_id'], 'first_name');
        $results['assignee'] = User::getUserFirstName($results['tester_id'], 'first_name');
        $results['tickets']  = unserialize($results['tickets']);

        return $results;
    }

    /**
     * Prepare columns for header
     *
     * This function must be implemented whenever table is rendered
     *
     * @param $order
     * @return mixed
     */
    public static function prepareColumns($order, $columnsToDisplay)
    {
        $columns['description'] = [
            'type'       => 'text',
            'colname'    => 'Description',
            'data'       => ['class' => 'form-control input-sm', 'id' => 'search-term'],
            'sortable'   => 'description',
            'order'      => $order,
            'filterable' => true,
            'width'      => '100px'
        ];

        $columns['first_name'] = [
            'type'       => 'text',
            'colname'    => 'Creator',
            'data'       => ['class' => 'form-control input-sm', 'id' => 'search-term'],
            'sortable'   => 'first_name',
            'order'      => $order,
            'filterable' => true,
            'width'      => '40px'
        ];

        $columns['status'] = [
            'type'       => 'text',
            'colname'    => 'Status',
            'data'       => ['class' => 'form-control input-sm', 'id' => 'search-term'],
            'sortable'   => 'status',
            'order'      => $order,
            'filterable' => true,
            'width'      => '20px'
        ];

        $columns['created_at'] = [
            'type'       => 'date',
            'colname'    => 'Created',
            'from_data'  => ['class' => 'form-control input-sm', 'id' => 'created_from'],
            'to_data'    => ['class' => 'form-control input-sm', 'id' => 'created_to'],
            'from_index' => 'created_from',
            'to_index'   => 'created_to',
            'sortable'   => 'created_at',
            'order'      => $order,
            'width'      => '60px'
        ];

        $columns['updated_at'] = [
            'type'    => 'text',
            'colname' => 'Updated',
            'width'   => '60px'
        ];

        foreach($columnsToDisplay as $column) {
            $results = Grid::addColumn($column, $columns[$column]);
        }

        return $results;
    }

    /**
     * Prepare table for view
     *
     * @param $order
     * @param $columnToDisplay
     * @param bool $showSort
     * @param bool $showFilter
     * @return mixed
     */
    public static function prepareTable($order, $columnToDisplay, $showSort = true, $showFilter = true)
    {
        $preparedColumns = self::prepareColumns($order, $columnToDisplay);

        if (!$showSort || !$showFilter) {
            foreach($preparedColumns as $column) {
                if (!$showSort) {
                    $column['sortable'] = null;
                }

                if (!$showFilter) {
                    $column['filterable'] = false;
                }

                $columns[] = $column;
            }
        }

        $table['columns']      = !$showSort || !$showFilter ? $columns : $preparedColumns;
        $table['columns_link'] = 'PlansController@index';

        return $table;
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
}