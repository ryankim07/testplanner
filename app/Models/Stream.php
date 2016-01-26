<?php namespace App\Models;

/**
 * Class ActivityStream
 *
 * Model
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

use Illuminate\Database\Eloquent\Model;

use App\Facades\Tools;

use Auth;

class Stream extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "activity_stream";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plan_id',
        'user_id',
        'activity'
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
    protected $appends = array('custom_activity');

    /**
     * Retrieve custom accessor
     *
     * @return mixed
     */
    public function getCustomActivityAttribute()
    {
        return Auth::user()->id == $this->user_id ? $this->activity : strip_tags($this->activity);
    }

    /**
     * Calculate and convert to a human readable format
     *
     * @param $value
     * @return mixed
     */
    public function getCreatedAtAttribute($value)
    {
        return Tools::timeDifference($value);
    }

    /**
     * One activity stream could have multiple comments
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Models\Comments', 'as_id', 'id');
    }
}