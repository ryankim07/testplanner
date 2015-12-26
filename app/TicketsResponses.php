<?php namespace App;

/**
 * Class TicketsResponses
 *
 * Model
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

use Illuminate\Database\Eloquent\Model;

class TicketsResponses extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table      = "tickets_responses";
    protected $primaryKey = "id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plan_id',
        'tester_id',
        'status',
        'responses'
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
     * Save user's ticket responses
     *
     * @param $planData
     * @return string
     */
    public static function saveTicketResponse($planData)
    {
        $completed    = 0;
        $incompleted  = 0;
        $totalTickets = count($planData['tickets_responses']);

        foreach($planData['tickets_responses'] as $ticket) {
            if (!isset($ticket['test_status'])) {
                $incompleted += 1;
            } else {
                $completed += 1;
            }
        }

        if ($incompleted == $totalTickets) {
            $ticketStatus = 'new';
        } else {
            $ticketStatus = 'incomplete';
        }

        if ($completed == $totalTickets) {
            $ticketStatus = 'complete';
        }

        // Create or update ticket response
        TicketsResponses::updateOrCreate([
            'id' => $planData['ticket_resp_id']], [
                'plan_id'   => $planData['id'],
                'tester_id' => $planData['tester_id'],
                'responses' => serialize($planData['tickets_responses']),
                'status'    => $ticketStatus
            ]
        );

        return $ticketStatus;
    }
}