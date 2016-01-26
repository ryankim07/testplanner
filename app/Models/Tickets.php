<?php namespace App\Models;

/**
 * Class Tickets
 *
 * Model
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

use Illuminate\Database\Eloquent\Model;

class Tickets extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "tickets";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plan_id',
        'tickets'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = array('id');


    /**
     * Only one task belongs to a case
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan()
    {
        return $this->belongsTo('App\Plans');
    }
}