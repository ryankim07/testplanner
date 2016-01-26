<?php namespace App\Api;

/**
 * Class UserApi
 *
 * Custom Model
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use PhpSpec\Exception\Exception;
use App\Api\Abstracts\BaseApi;

use App\Facades\Tools;

use App\Api\TablesApi;

use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;

class UserApi extends BaseApi
{
    /**
     * @var Plans
     */
    protected $userModel;
    protected $userRoleModel;
    protected $tablesApi;

    /**
     * Users constructor.
     *
     * @param User $user
     */
    public function __construct(User $user, UserRole $userRole, TablesApi $tables)
    {
        $this->userModel     = $user;
        $this->userRoleModel = $userRole;
        $this->tablesApi     = $tables;
    }

    /**
     * Get user's info
     *
     * @param $userId
     * @return mixed
     */
    public function getUserFirstName($userId)
    {
        $info = $this->userModel->find($userId);

        return $info->first_name;
    }

    /**
     * Get user's email
     *
     * @param $userId
     * @return mixed
     */
    public function getUserEmail($userId)
    {
        $info = $this->userModel->find($userId);

        return $info->email;
    }

    /**
     * Show user for adding or editing
     *
     * @param $info
     * @return array
     */
    public function displayUser($info)
    {
        $results = [];

        list($id, $firstName, $lastName, $email, $active, $userRoles) = explode(':', $info);

        $results['user_roles'] = explode(',', $userRoles);

        $results['user'] = [
            'id'         => $id,
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'email'      => $email,
            'active'     => $active,
        ];

        // Prepare dropdown list of all roles
        $allRoles  = Role::all();

        foreach($allRoles as $eachRole) {
            $rolesOptions[$eachRole->id] = $eachRole->custom_role_name;
        }

        $results['role_options'] = count($rolesOptions) > 0 ? $rolesOptions : '';

        return $results;
    }

    /**
     * Get all users
     *
     * @param $sortBy
     * @param $order
     * @return mixed
     */
    public function getAllUsersQuery($sortBy, $order)
    {
        $sortBy = 'sub.' . $sortBy;

        $sub = $this->baseUsersQuery()
            ->select('u.*', 'ur.role_id AS role_ids', 'r.name AS role_names')
            ->toSql(); // Do not remove this

        $query = DB::table(DB::raw("($sub) AS sub"))
            ->select('sub.*', DB::raw("GROUP_CONCAT(sub.role_ids SEPARATOR ', ') AS role_ids"), DB::raw("GROUP_CONCAT(sub.role_names ORDER BY sub.role_names SEPARATOR ', ') AS role_names"))
            ->groupBy('sub.id')
            ->orderBy($sortBy, $order);

        return $query;
    }

    public function getAllUsers()
    {
        $table = $this->tablesApi->prepare('order', [
            'first_name',
            'last_name',
            'email',
            'active',
            'role_names',
            'created_at',
            'updated_at',
            'edit'
        ], 'UsersController@view');

        $query = $this->getAllUsersQuery($table['sorting']['sortBy'], $table['sorting']['order']);
        $users = $query->paginate(config('testplanner.tables.pagination.lists'));

        $results = [
            'users'         => $users,
            'columns'       => $table['columns'],
            'columnsLink'   => $table['columns_link']

        ];

        return $results;
    }

    /**
     * Get all users by role
     *
     * @param $role
     * @return mixed
     */
    public function getAllUsersByRole($role)
    {
        $query = $this->baseUsersQuery()
            ->select('u.*')
            ->whereIn('r.name', $role)
            ->groupBy('r.name')
            ->get();

        return $query;
    }

    public function getUsersDropdrownOptions()
    {
        $list = $this->getAllUsersByRole(['root', 'administrator']);

        // Set up dropdown list of all admins
        $results[0] = 'All';

        foreach($list as $each) {
            $results[$each->id] = $each->first_name;
        }

        return $results;
    }

    /**
     * Update user account information
     *
     * @param $request
     * @return bool
     */
    public function updateUser($request)
    {
        $redirect = false;
        $errorMsg = '';

        // Start transaction
        DB::beginTransaction();

        // Create new user
        try {
            $userId = $request->get('user_id');

            // Update user info
            $user   = $this->userModel->find($userId);
            $update = $user->update([
                'first_name' => $request->get('first_name'),
                'last_name'  => $request->get('last_name'),
                'email'      => $request->get('email'),
                'active'     => $request->get('active'),
                'password'   => bcrypt($request->get('password'))
            ]);

            // Remove all existing roles for user
            if (isset($user->id)) {
                $user->roles()->delete();
            }

            // Update user roles
            $newRoles = explode(',', $request->get('role'));

            if (count($newRoles) > 0) {
                foreach ($newRoles as $key => $value) {
                    $this->userRoleModel->create([
                        'user_id' => $userId,
                        'role_id' => $value
                    ]);
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

            // Log specific technical message
            Tools::log($errorMsg, array_except($request->all(), [
                '_token',
                'created_from',
                'created_to',
                'password',
                'password_confirmation'
            ]));

            return false;
        }

        // Commit all changes
        DB::commit();

        return true;
    }
}