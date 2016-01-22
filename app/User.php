<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use PhpSpec\Exception\Exception;

use App\Facades\Tools;

use App\Role;
use App\UserRole;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'role_id',
        'email',
        'password',
        'active'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = array('id');

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Capitalize the first name when saving to the database
     *
     * @param $value
     */
    public function setFirstNameAttribute($value) {
        $this->attributes['first_name'] = ucfirst($value);
    }

    /**
     * Capitalize the last name when saving to the database
     */
    public function setLastNameAttribute($value) {
        $this->attributes['last_name'] = ucfirst($value);
    }

    /**
     * Get role
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function role()
    {
        return $this->belongsToMany('App\Role', 'user_role', 'user_id', 'role_id');
    }

    /**
     * User has many roles
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function roles()
    {
        return $this->hasMany('App\UserRole', 'user_id', 'id');
    }

    /**
     * Check if has a specific role
     *
     * @param $roles
     * @param null $section
     * @return bool
     */
    public function hasRole($roles, $section = null)
    {
        // Get all user's roles
        $userRoles = $this->role()->getResults();

        // Check which section they belond to
        foreach($userRoles as $hasRole) {
            if (in_array($hasRole->name, $roles)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get user's info
     *
     * @param $userId
     * @return mixed
     */
    public static function getUserFirstName($userId)
    {
        $info = self::find($userId);

        return $info->first_name;
    }

    /**
     * Get user's email
     *
     * @param $userId
     * @return mixed
     */
    public static function getUserEmail($userId)
    {
        $info = self::find($userId);

        return $info->email;
    }

    /**
     * Show user for adding or editing
     *
     * @param $info
     * @return array
     */
    public static function displayUser($info)
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
    public static function getAllUsers($sortBy, $order)
    {
        $sortBy = 'sub.' . $sortBy;

        $sub = DB::table('users as u')
            ->leftJoin('user_role AS ur', 'ur.user_id', '=', 'u.id')
            ->leftJoin('roles AS r', 'r.id', '=', 'ur.role_id')
            ->select('u.*', 'ur.role_id AS role_ids', 'r.name AS role_names')
            ->toSql(); // Do not remove this

        $query = DB::table(DB::raw("($sub) AS sub"))
            ->select('sub.*', DB::raw("GROUP_CONCAT(sub.role_ids SEPARATOR ', ') AS role_ids"), DB::raw("GROUP_CONCAT(sub.role_names SEPARATOR ', ') AS role_names"))
            ->groupBy('sub.id')
            ->orderBy($sortBy, $order);

        return $query;
    }

    /**
     * Get all users by role
     *
     * @param $role
     * @return mixed
     */
    public static function getAllUsersByRole($role)
    {
        $query = DB::table('users as u')
            ->leftJoin('user_role AS ur', 'ur.user_id', '=', 'u.id')
            ->leftJoin('roles AS r', 'r.id', '=', 'ur.role_id')
            ->select('u.*')
            ->where('r.name', '=', $role)
            ->get();

        return $query;
    }

    /**
     * Update user account information
     *
     * @param $request
     * @return bool
     */
    public static function updateUser($request)
    {
        $redirect = false;
        $errorMsg = '';

        // Start transaction
        DB::beginTransaction();

        // Create new user
        try {
            $userId = $request->get('user_id');

            // Update user info
            $user = self::find($userId);

            $user->update([
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'email' => $request->get('email'),
                'active' => $request->get('active'),
                'password' => bcrypt($request->get('password'))
            ]);

            // Remove all existing roles for user
            if (isset($userId)) {
                UserRole::where('user_id', $userId)->delete();
            }

            // Update user roles
            $newRoles = explode(',', $request->get('role'));

            if (count($newRoles) > 0) {
                foreach ($newRoles as $key => $value) {
                    UserRole::create([
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