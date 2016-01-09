<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use Illuminate\Support\Facades\DB;

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
        $info = User::find($userId);

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
        $info = User::find($userId);

        return $info->email;
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
            ->select('u.*', 'r.name AS role_names')
            ->toSql();

        $query = DB::table(DB::raw("($sub) AS sub"))
            ->select('sub.*', DB::raw("GROUP_CONCAT(sub.role_names SEPARATOR ', ') AS role_names"))
            ->groupBy('sub.id')
            ->orderBy($sortBy, $order);

        return $query;
    }

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
}