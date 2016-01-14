<?php namespace App;

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
     * Model event to change data before saving to database
     */
    public static function boot()
    {
    }

    /**
     * Update tickets from built plan
     *
     * @param $planId
     * @param $tickets
     * @return bool
     */
    public static function updateBuiltPlanTickets($planId, $tickets)
    {
        $plan = Tickets::where('plan_id', '=', $planId);
        $plan->update(['tickets' => $tickets]);

        return true;
    }

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