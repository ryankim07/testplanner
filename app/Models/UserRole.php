<?php namespace App\Models;

/**
 * Class UserRole
 *
 * Model
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_role';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'role_id',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = array('id');

    public function addRoles($userId, $selectedRoles)
    {
        try {
            foreach ($selectedRoles as $key => $id) {
                $this->create([
                    'user_id' => $userId,
                    'role_id' => $id
                ]);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Only one role belongs to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}