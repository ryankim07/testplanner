<?php namespace App\Models;

/**
 * Class Comments
 *
 * Model
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://tp.nophie.us)
 */

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    /**
     * The database table used by the model
     *
     * @var string
     */
    protected $table = "comments";

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'stream_id',
        'user_id',
        'comment'
    ];

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $guarded = array('id');

    /**
     * Custom attribute to be included in model
     *
     * @var array
     */
    protected $appends = array('user_first_name');

    /**
     * Retrieve custom accessor
     *
     * @return mixed
     */
    public function getUserFirstNameAttribute()
    {
        return Tools::getUserFirstName($this->user_id);
    }

    /**
     * Date accessor
     *
     * @param $value
     * @return mixed
     */
    public function getCreatedAtAttribute($value)
    {
        return date('m/d/Y', strtotime($value));
    }

    /**
     * Only one comment belongs to an activity stream
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function stream()
    {
        return $this->belongsTo('App\Models\Streams');
    }
}