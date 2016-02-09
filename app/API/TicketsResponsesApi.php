<?php namespace App\Api;

/**
 * Class TicketsResponsesApi
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

use App\Models\TicketsResponses;

class TicketsResponsesApi
{
    /**
     * @var Tickets Respones
     */
    protected $model;

    /**
     * TicketsApi constructor
     *
     * @param TicketsResponses $tr
     */
    public function __construct(TicketsResponses $tr)
    {
        $this->model = $tr;
    }

    /**
     * Save tester's ticket responses
     *
     * @param $planData
     * @return string
     */
    public function saveResponse($planData)
    {
        $totalTickets = count($planData['tickets_responses']);
        $allStatus    = [];
        $redirect     = false;
        $errorMsg     = '';

        DB::beginTransaction();

        try {
            // Determine ticket status
            foreach($planData['tickets_responses'] as $browser => $rows) {
                $totalRows  = count($rows['tickets']);
                $completed  = 0;
                $incomplete = 0;

                foreach($rows['tickets'] as $ticket) {
                    if (!isset($ticket['test_status'])) {
                        $incomplete += 1;
                    } else {
                        $completed += 1;
                    }
                }

                if ($incomplete == $totalRows) {
                    $ticketStatus = 'new';
                } elseif ($completed == $totalRows) {
                    if ($rows['ticket_status'] == 'complete' && $ticket['original_data'] == 'modified') {
                        $ticketStatus = 'update';
                    } else {
                        $ticketStatus = 'complete';
                    }
                } else {
                    $ticketStatus = 'progress';
                }

                // Collect status for every tickets
                $allStatus[] = $ticketStatus;

                // Create or update ticket response
                $this->model->updateOrCreate([
                    'id' => $rows['ticket_resp_id']
                ], [
                    'plan_id'   => $planData['plan_id'],
                    'tester_id' => $planData['tester_id'],
                    'browser'   => $browser,
                    'responses' => serialize($rows['tickets']),
                    'status'    => $ticketStatus,
                ]);
            }
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

        // Redirect if errors
        if ($redirect) {
            // Rollback
            DB::rollback();

            // Log to system
            Tools::log($errorMsg, $planData);

            return false;
        }

        // Commit all changes
        DB::commit();

        $results = Tools::getOverallStatus($allStatus, $totalTickets);

        return $results;
    }
}