<?php namespace App;

/**
 * Class Tables
 *
 * Model
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Facades\Utils;
use App\Facades\Grid;

use Config;

class Tables extends Model
{
    /**
     * Returns sort and order of columns
     *
     * @return array
     */
    public static function sorting()
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
     * @param $searchType
     * @param $query
     * @return mixed
     */
    public static function searchResults($searchType, $query)
    {
        $column = [
            'customers'     => 'id',
            'registrations' => 'registration_code',
            'services'      => 'id',
            'payments'      => 'id'
        ];

        $searchTerms = Request::input();
        $filters     = array_except($searchTerms, ['_token', 'created_from', 'created_to', 'sortBy', 'order', 'page']);
        $perPage     = config('testplanner.pagination_count');
        $page        = isset($searchTerms['page']) ? $searchTerms['page'] : 1;
        $url         = parse_url(Request::url());

        // Default sort and order
        $sortBy = empty($searchTerms['sortBy']) ? 'created_at' : $searchTerms['sortBy'];
        $order  = empty($searchTerms['order'])  ? 'DESC' :$searchTerms['order'];
        $from   = !empty($searchTerms['created_from']) ? Utils::dbDateConverter($searchTerms['created_from'], '00:00:00') : null;
        $to     = !empty($searchTerms['created_to'])   ? Utils::dbDateConverter($searchTerms['created_to'], '23:59:59') : null;

        foreach($filters as $key => $value) {
            if (!empty($value)) {
                $query->where($key, 'LIKE', '%' . $value . '%');
            }
        }

        if (isset($from) && isset($to)) {
            $query->whereBetween('created_at', [$from, $to]);
        }

        $totalCount = $query->count();

        $query->orderBy($sortBy, $order)
            ->take($perPage)
            ->offset(($page-1) * $perPage);

        //$query->where($column[$searchType], $param);

        // Manual paginator
        if (isset($searchTerms['page'])) {
            $list = new LengthAwarePaginator($query->get(), $totalCount, $perPage, $page, ["path" => $url['path']]);
        } else {
            $list = $query->paginate(config('testplanner.pagination_count'));
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
    public static function prepare($type, $columnToDisplay, $columnLink)
    {
        $sorting         = self::sorting();
        $preparedColumns = Grid::prepareColumns($sorting[$type], $columnToDisplay);

        $table['sorting']      = $sorting;
        $table['columns']      = $preparedColumns;
        $table['columns_link'] = $columnLink;

        return $table;
    }
}