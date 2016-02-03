<?php namespace app\Api;

/**
 * Class UserRoleApi
 *
 * Custom Model
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://tp.mophie.us)
 */

use App\Models\UserRole;

class UserRoleApi
{
    /**
     * @var UserRole
     */
    protected $model;

    /**
     * UserRoleApi constructor
     *
     * @param UserRole $userRole
     */
    public function __construct(UserRole $userRole)
    {
        $this->model = $userRole;
    }

    /**
     * Add roles for new user
     *
     * @param $userId
     * @param $selectedRoles
     * @throws \Exception
     */
    public function addRoles($userId, $selectedRoles)
    {
        try {
            foreach ($selectedRoles as $key => $id) {
                $this->model->create([
                    'user_id' => $userId,
                    'role_id' => $id
                ]);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}