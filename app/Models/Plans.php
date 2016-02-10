<?php namespace App\Models;

/**
 * Class Plans
 *
 * Model
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://tp.nophie.us)
 */

use Illuminate\Database\Eloquent\Model;

class Plans extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "plans";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description',
        'jira_bvid',
        'creator_id',
        'status',
        'started_at',
        'expired_at'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = array('id');

    /**
     * Format to datetime when saving to database
     *
     * @param $value
     */
    public function setStartedAtAttribute($value)
    {
        $this->attributes['started_at'] = date('Y-m-d H:i:s', strtotime($value));
    }

    /**
     * Format to datetime when saving to database
     *
     * @param $value
     */
    public function setExpiredAtAttribute($value)
    {
        $this->attributes['expired_at'] = date('Y-m-d 23:59:59', strtotime($value));
    }

    /**
     * Always capitalize the first name when saving to the database
     *
     * @param $value
     */
    public function setFirstNameAttribute($value) {
        $this->attributes['first_name'] = ucfirst($value);
    }

    /**
     * One plan could have multiple tickets
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ticket()
    {
        return $this->hasOne('App\Models\Tickets', 'plan_id', 'id');
    }

    /**
     * One plan could have multiple testers
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function testers()
    {
        return $this->hasMany('App\Models\Testers', 'plan_id', 'id');
    }

    public function ticketsResponses()
    {
        return $this->hasMany('App\Models\TicketsResponses', 'plan_id', 'id');
    }

    /**
     * Scope a query to only include creator
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCreator($query, $userId)
    {
        return $query->where('p.creator_id', '=', $userId);
    }
}