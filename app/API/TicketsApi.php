<?php namespace app\Api;

/**
 * Class TicketsApi
 *
 * Custom Model
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://tp.mophie.us)
 */

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use PhpSpec\Exception\Exception;

use App\Facades\Tools;

use App\Models\Tickets;

class TicketsApi
{
    /**
     * @var Tickets
     */
    protected $model;

    /**
     * TicketsApi constructor
     *
     * @param Tickets $tickets
     */
    public function __construct(Tickets $tickets)
    {
        $this->model = $tickets;
    }

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
            $ticket = $this->model->where('plan_id', '=', $planId);
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
}