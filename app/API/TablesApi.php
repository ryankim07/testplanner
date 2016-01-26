<?php namespace App\Api;

/**
 * Class TablesApi
 *
 * Custom Model
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Facades\Tools;

use App\Api\Grid;

class TablesApi
{
    /**
     * @var GridApi
     */
    protected $gridApi;

    /**
     * Tables constructor.
     *
     * @param \App\Api\GridApi $grid
     */
    public function __construct(GridApi $grid)
    {
        $this->gridApi = $grid;
    }
    /**
     * Returns sort and order of columns
     *
     * @return array
     */
    public function sorting()
    {
        $sortBy = Request::input('sortBy');
        $order  = Request::input('order');

        // Default sort and order
        $sortBy = empty($sortBy) ? 'created_at' : $sortBy;
        $order  = empty($order)  ? 'DESC' : $order;

        return ['sortBy' => $sortBy, 'order' => $order];
    }

    /**
     * Return search results from filters or links
     *
     * @param $query
     * @return mixed
     */
    public function searchResults($query)
    {
        $searchTerms = Request::input();

        // Remove certain keys when querying
        $filters     = array_except($searchTerms, ['_token', 'admin', 'created_from', 'created_to', 'sortBy', 'order', 'page']);
        $perPage     = 1;
        $page        = isset($searchTerms['page']) ? $searchTerms['page'] : 1;
        $url         = parse_url(Request::url());

        // Default sort and order
        $sortBy = empty($searchTerms['sortBy']) ? 'p.created_at' : $searchTerms['sortBy'];
        $order  = empty($searchTerms['order'])  ? 'DESC' :$searchTerms['order'];
        $from   = !empty($searchTerms['created_from']) ? Tools::dbDateConverter($searchTerms['created_from'], '00:00:00') : null;
        $to     = !empty($searchTerms['created_to'])   ? Tools::dbDateConverter($searchTerms['created_to'], '23:59:59') : null;

        if (isset($filters['first_name']) && isset($filters['first_name'])) {
            $query->join('users AS u', function($join) use ($filters) {
                $join->on('u.id', '=', 'p.creator_id')
                    ->where('u.first_name', 'LIKE', '%' . $filters['first_name'] . '%')
                    ->where('u.last_name', 'LIKE', '%' . $filters['last_name'] . '%');
            });
        }

        foreach(array_except($filters, ['first_name', 'last_name']) as $key => $value) {
            if (!empty($value)) {
                $query->where('p.' . $key, 'LIKE', '%' . $value . '%');
            }
        }

        $query->select('p.*', 'u.first_name', 'u.last_name');

        if (isset($from) && isset($to)) {
            $query->whereBetween('p.created_at', [$from, $to]);
        }

        $totalCount = $query->count();

        $query->orderBy($sortBy, $order)
            ->take($perPage)
            ->offset(($page-1) * $perPage);

        // Manual paginator
        if (isset($searchTerms['page'])) {
            $list = new LengthAwarePaginator($query->get(), $totalCount, $perPage, $page, ["path" => $url['path']]);
        } else {
            $list = $query->paginate($perPage);
        }

        $results['list']       = $list;
        $results['totalCount'] = $totalCount;
        $results['order']      = $order;
        $results['link']       = array_except($searchTerms, ['_token', 'page']);

        return $results;
    }

    /**
     * Prepare table for view
     *
     * @param $type
     * @param $columnToDisplay
     * @param $columnLink
     * @return mixed
     */
    public function prepare($type, $columnToDisplay, $columnLink)
    {
        $sorting         = $this->sorting();
        $preparedColumns = $this->gridApi->prepareColumns($sorting[$type], $columnToDisplay);

        $table['sorting']      = $sorting;
        $table['columns']      = $preparedColumns;
        $table['columns_link'] = $columnLink;

        return $table;
    }
}