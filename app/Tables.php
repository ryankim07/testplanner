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
     * Prepare columns for header
     *
     * This function must be implemented whenever table is rendered
     *
     * @param $order
     * @param $columnsToDisplay
     * @return mixed
     */
    public static function prepareColumns($order, $columnsToDisplay)
    {
        $columns['first_name'] = [
            'type'       => 'text',
            'colname'    => 'First',
            'data'       => ['class' => 'form-control input-sm', 'id' => 'search-term'],
            'sortable'   => 'first_name',
            'order'      => $order,
            'filterable' => true,
            'width'      => '40px'
        ];

        $columns['last_name'] = [
            'type'       => 'text',
            'colname'    => 'Last',
            'data'       => ['class' => 'form-control input-sm', 'id' => 'search-term'],
            'sortable'   => 'last_name',
            'order'      => $order,
            'filterable' => true,
            'width'      => '40px'
        ];

        $columns['admin'] = [
            'type'       => 'text',
            'colname'    => 'Name',
            'data'       => ['class' => 'form-control input-sm', 'id' => 'search-term'],
            'sortable'   => 'admin',
            'order'      => $order,
            'filterable' => true,
            'width'      => '80px'
        ];

        $columns['email'] = [
            'type'       => 'text',
            'colname'    => 'Email',
            'data'       => ['class' => 'form-control input-sm', 'id' => 'search-term'],
            'sortable'   => 'email',
            'order'      => $order,
            'filterable' => true,
            'width'      => '80px'
        ];

        $columns['active'] = [
            'type'       => 'text',
            'colname'    => 'Active',
            'data'       => ['class' => 'form-control input-sm', 'id' => 'search-term'],
            'sortable'   => 'email',
            'order'      => $order,
            'width'      => '10px'
        ];

        $columns['role_names'] = [
            'type'       => 'text',
            'colname'    => 'Roles',
            'data'       => ['class' => 'form-control input-sm', 'id' => 'search-term'],
            'sortable'   => '',
            'order'      => '',
            'filterable' => false,
            'width'      => '100px'
        ];

        $columns['description'] = [
            'type'       => 'text',
            'colname'    => 'Description',
            'data'       => ['class' => 'form-control input-sm', 'id' => 'search-term'],
            'sortable'   => 'description',
            'order'      => $order,
            'filterable' => true,
            'width'      => '100px'
        ];

        $columns['status'] = [
            'type'       => 'text',
            'colname'    => 'Status',
            'data'       => ['class' => 'form-control input-sm', 'id' => 'search-term'],
            'sortable'   => 'status',
            'order'      => $order,
            'filterable' => true,
            'width'      => '10px'
        ];

        $columns['activity'] = [
            'type'       => 'text',
            'colname'    => 'Activity',
            'filterable' => true,
            'width'      => '100px'
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
            'width'      => '40px'
        ];

        $columns['updated_at'] = [
            'type'     => 'text',
            'colname'  => 'Updated',
            'width'    => '40px'
        ];

        $columns['testers'] = [
            'type'       => 'text',
            'colname'    => 'Testers',
            'data'       => ['class' => 'form-control'],
            'order'      => '',
            'width'      => '30px'
        ];

        $columns['view'] = [
            'type'         => 'text',
            'colname'      => 'View',
            'data'         => ['class' => 'form-control'],
            'order'        => '',
            'width'        => '10px',
            'header_align' => 'center'
        ];

        $columns['respond'] = [
            'type'         => 'text',
            'colname'      => 'Respond',
            'data'         => ['class' => 'form-control'],
            'order'        => '',
            'width'        => '10px',
            'header_align' => 'center'
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
    public static function prepareTable($order, $columnToDisplay, $columnLink)
    {
        $preparedColumns = self::prepareColumns($order, $columnToDisplay);

        foreach($preparedColumns as $column) {
            if (!isset($column['sortable'])) {
                $column['sortable'] = null;
            }

            if (!isset($column['filterable'])) {
                $column['filterable'] = false;
            }

            if (!isset($column['headera_align'])) {
                $column['header_align'] = 'left';
            }

            $columns[] = $column;
        }

        $table['columns']      = $columns;
        $table['columns_link'] = $columnLink;

        return $table;
    }
}