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
    public static function updateBuiltTickets($planId, $tickets)
    {
        $tickets = json_decode($tickets, true);
        $ticket  = self::where('plan_id', '=', $planId);
        $ticket->update(['tickets' => serialize($tickets)]);

        return true;
    }

    /**
     * Remove from ticket session data
     *
     * @param $ticketsData
     * @param $ticketId
     */
    public static function removeTicketFromSession($ticketsData, $ticketId)
    {
        foreach($ticketsData as $ticket) {
            $modifiedData[$ticket['id']] = $ticket;
        }

        // Remove
        unset($modifiedData[$ticketId]);
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