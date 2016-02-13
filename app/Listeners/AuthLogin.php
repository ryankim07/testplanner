<?php namespace App\Listeners;

/**
 * Class AuthLogout
 *
 * Listener
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://tp.nophie.us)
 */

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Api\UserApi;
use App\Models\User;

use Session;

class AuthLogin
{
    /**
     * @var UserApi
     */
    protected $userApi;

    /**
     * AuthLogin constructor
     */
    public function __construct(UserApi $userApi)
    {
        $this->userApi = $userApi;
    }

    /**
     * Handle the event
     *
     * @param User $user
     * @param $remember
     */
    public function handle(User $user, $remember)
    {
        $userSession     = Session::get('mophie.user');
        $allUsersSession = Session::get('mophie.all_users');

        // Save current user to session
        if (!$userSession) {
            $userRoles = $user->role()->get();

            foreach($userRoles as $role) {
                $roles[$role->id] = $role->name;
            }

            Session::put('mophie.user', [
                'id'         => $user->id,
                'first_name' => $user->first_name,
                'last_name'  => $user->last_name,
                'email'      => $user->email,
                'active'     => $user->active,
                'roles'      => $roles
            ]);
        }

        // Save all users to session
        if (!$allUsersSession) {
            $allUsers = $this->userApi->usersList();

            Session::put('mophie.all_users', $allUsers);
        }
    }
}
