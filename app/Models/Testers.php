<?php namespace App\Models;

/**
 * Class Testers
 *
 * Model
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

use Illuminate\Database\Eloquent\Model;

class Testers extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "testers";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plan_id',
        'user_id',
        'browsers'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = array('id');

    /**
     * Custom attribute to be included in model
     *
     * @var array
     */
    protected $appends = array(
        'user_first_name',
        'user_browsers'
    );

    /**
     * Retrieve first name accessor
     *
     * @return mixed
     */
    public function getUserFirstNameAttribute()
    {
        $user = User::find($this->user_id);

        return $user->first_name;
    }

    /**
     * Retrieve browser name accessor
     *
     * @return string
     */
    public function getUserBrowsersAttribute()
    {
        return implode(', ', array_map('ucfirst', explode(',', $this->browsers)));
    }

    /**
     * Only one task belongs to a case
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan()
    {
        return $this->belongsTo('App\Models\Plans');
    }
}