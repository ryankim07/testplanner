<?php namespace App\Api\Abstracts;

/**
 * Class BaseApi
 *
 * Abstract
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://tp.mophie.us)
 */

use Illuminate\Support\Facades\DB;

abstract class BaseApi
{
    /**
     * Base supporting query for other plan queries
     *
     * @return mixed
     */
    public function basePlansUsersQuery()
    {
        $query = DB::table('plans AS p')
            ->select('p.*', 'u.first_name', 'u.last_name')
            ->join('users AS u', 'u.id', '=', 'p.creator_id');

        return $query;
    }

    /**
     * Base supporting query for other users queries
     *
     * @return mixed
     */
    public function baseUsersQuery()
    {
        $query = DB::table('users as u')
            ->leftJoin('user_role AS ur', 'ur.user_id', '=', 'u.id')
            ->leftJoin('roles AS r', 'r.id', '=', 'ur.role_id');

        return $query;
    }

    /**
     * Get all plans created by a certain administrator
     *
     * @param $sortBy
     * @param $order
     * @param null $userId
     * @return mixed
     */
    public function getAllPlans($sortBy, $order, $userId = null)
    {
        $query = $this->basePlansUsersQuery();

        if (!empty($userId)) {
            $query->where('p.creator_id', '=', $userId);
        }

        $query->orderBy($sortBy, $order);

        return $query;
    }
}