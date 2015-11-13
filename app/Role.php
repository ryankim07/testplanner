<?php namespace App;

/**
 * Class Role
 *
 * Model
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2015 mophie (https://lpp.nophie.com)
 */

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * Get admin users
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany('App\User', 'role_id', 'id');
    }
}