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
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use PhpSpec\Exception\Exception;

use App\Facades\Tools;

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
     * Update tickets from built plan
     *
     * @param $planId
     * @param $ticketsData
     * @return bool
     */
    public function updateBuiltTickets($planId, $ticketsData)
    {
        $redirect = false;
        $errorMsg = '';

        // Start transaction
        DB::beginTransaction();

        // Start tickets update
        try {
            $ticketsData = json_decode($ticketsData, true);
            $ticket      = self::where('plan_id', '=', $planId);
            $ticket->update(['tickets' => serialize($ticketsData)]);
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            $redirect = true;
        } catch (QueryException $e) {
            $errorMsg = $e->getErrors();
            $redirect = true;
        } catch (ModelNotFoundException $e) {
            $errorMsg = $e->getErrors();
            $redirect = true;
        }

        // Redirect if errors
        if ($redirect) {
            // Rollback
            DB::rollback();

            // Log to system
            Tools::log($errorMsg, $ticketsData);

            return false;
        }

        // Commit all changes
        DB::commit();

        return true;
    }

    /**
     * Remove from ticket session data
     *
     * @param $ticketsData
     * @param $ticketId
     */
    public function removeTicketFromSession($ticketsData, $ticketId)
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