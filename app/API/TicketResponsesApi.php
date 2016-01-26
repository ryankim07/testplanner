<?php namespace app\Api;

/**
 * Class TicketResponsesApi
 *
 * Custom Model
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use PhpSpec\Exception\Exception;

use App\Facades\Tools;

class TicketResponsesApi
{
    /**
     * Save tester's ticket responses
     *
     * @param $planData
     * @return string
     */
    public function saveResponse($planData)
    {
        $completed    = 0;
        $progress     = 0;
        $incomplete   = 0;
        $totalTickets = count($planData['tickets_responses']);
        $redirect     = false;
        $errorMsg     = '';

        // Determine ticket status
        foreach($planData['tickets_responses'] as $ticket) {
            if (!isset($ticket['test_status'])) {
                $incomplete += 1;
            } else {
                $completed += 1;
            }
        }

        if ($incomplete == $totalTickets) {
            $ticketStatus = 'new';
        } elseif ($completed == $totalTickets) {
            if ($planData['ticket_status'] == 'complete') {
                $ticketStatus = 'update';
            } else {
                $ticketStatus = 'complete';
            }
        } else {
            $ticketStatus = 'progress';
        }

        // Create or update ticket response
        if ($ticketStatus != 'new') {
            // Start transaction
            DB::beginTransaction();

            try {
                self::updateOrCreate([
                    'id' => $planData['ticket_resp_id']], [
                    'plan_id'   => $planData['id'],
                    'tester_id' => $planData['tester_id'],
                    'responses' => serialize($planData['tickets_responses']),
                    'status'    => $ticketStatus
                ]);

            } catch (\Exception $e) {
                $errorMsg = $e->getMessage();
                $redirect = true;
            } catch (ValidationException $e) {
                $errorMsg = $e->getErrors();
                $redirect = true;
            } catch (QueryException $e) {
                $errorMsg = $e->getErrors();
                $redirect = true;
            } catch (ModelNotFoundException $e) {
                $errorMsg = $e->getErrors();
                $redirect = true;
            }

            // Commit all changes
            DB::commit();

            // Redirect if errors
            if ($redirect) {
                // Rollback
                DB::rollback();

                // Log to system
                Tools::log($errorMsg, $planData);

                return false;
            }
        }

        return $ticketStatus;
    }
}