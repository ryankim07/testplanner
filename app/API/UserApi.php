<?php namespace App\Api;

/**
 * Class UserApi
 *
 * Custom Model
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://tp.mophie.us)
 */

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use PhpSpec\Exception\Exception;
use App\Api\Abstracts\BaseApi;

use App\Facades\Tools;

use App\Api\TablesApi;

use App\Models\User,
    App\Models\Role,
    App\Models\UserRole;

class UserApi extends BaseApi
{
    /**
     * @var Plans
     */
    protected $model;

    /**
     * @var UserRole
     */
    protected $userRoleModel;

    /**
     * @var \App\Api\TablesApi
     */
    protected $tablesApi;

    /**
     * Users constructor.
     *
     * @param User $user
     */
    public function __construct(User $user, UserRole $userRole, TablesApi $tablesApi)
    {
        $this->model         = $user;
        $this->userRoleModel = $userRole;
        $this->tablesApi     = $tablesApi;
    }

    /**
     * User's basic list
     */
    public function usersList()
    {
        $users = $this->model->all()->toArray();

        foreach($users as $user) {
            $results[$user['id']] = $user;
        }

        return $results;
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
        $rolesOptions = Tools::getRolesDropdownOptions();

        $results['role_options'] = count($rolesOptions) > 0 ? $rolesOptions : '';

        return $results;
    }

    /**
     * Get all users query
     *
     * @param $sortBy
     * @param $order
     * @return mixed
     */
    public function getAllUsersQuery($sortBy, $order)
    {
        $query = $this->baseUsersQuery()
            ->select('u.*', DB::raw("GROUP_CONCAT(ur.role_id SEPARATOR ', ') AS role_ids"), DB::raw("GROUP_CONCAT(r.name ORDER BY r.name SEPARATOR ', ') AS role_names"))
            ->groupBy('u.id')
            ->orderBy($sortBy, $order);

        return $query;
    }

    /**
     * List of all users with pagination
     *
     * @return array
     */
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
            'users'       => $users,
            'columns'     => $table['columns'],
            'columnsLink' => $table['columns_link']

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
            $user   = $this->model->find($userId);
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