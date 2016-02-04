<?php namespace App\Models;

/**
 * Class TicketsResponses
 *
 * Model
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://tp.nophie.us)
 */

use Illuminate\Database\Eloquent\Model;

class TicketsResponses extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "tickets_responses";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plan_id',
        'tester_id',
        'browser',
        'responses',
        'status'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = array('id');

    /**
     * Only one response belongs to a case
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tester()
    {
        return $this->belongsTo('App\Models\Testers');
    }

    public function plan()
    {
        return $this->belongsTo('App\Models\Plans');
    }
}