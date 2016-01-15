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
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use PhpSpec\Exception\Exception;

use App\Facades\Utils;

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
     * Save tester's ticket responses
     *
     * @param $planData
     * @return string
     */
    public static function saveResponse($planData)
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
            $ticketStatus = 'complete';
        } else {
            $ticketStatus = 'progress';
        }

        if ($planData['ticket_status'] == 'complete') {
            $ticketStatus = 'update';
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
                Utils::log($errorMsg, $planData);

                return redirect()->action('PlansController@respond')
                    ->withInput()
                    ->withErrors(array('message' => config('testplanner.plan_response_error_msg')));
            }

        }

        return $ticketStatus;
    }
}