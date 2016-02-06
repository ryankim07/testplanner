<?php namespace App\Api\Jobs;

/**
 * Class PlansJobs
 *
 * Observer
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://tp.nophie.us)
 */

use App\Models\Plans;


class PlansJobs
{
    /**
     * @var Plans
     */
    protected $model;

    /**
     * Plans constructor.
     *
     * @param Plans $plans
     */
    public function __construct(Plans $plans)
    {
        $this->model = $plans;
    }

    /**
     * Observer to change status of a plan according to all user's responses
     */
    public function updatePlanStatuses()
    {
        $status = [
            'new'      => 0,
            'progress' => 0,
            'update'   => 1,
            'complete' => 1
        ];

        $plans = $this->model->all();

        foreach($plans as $plan) {
            $totalResponses = 0;
            $statusTotal    = 0;
            $userResponses  = $plan->ticketsResponses()->get();
            $totalResponses = count($userResponses);

            foreach($userResponses as $ticket) {
                $statusTotal += $status[$ticket->status];
            }

            if ($statusTotal == 0) {
                $status = 'new';
            } elseif ($statusTotal == $totalResponses) {
                $status = 'complete';
            } else {
                $status = 'progress';
            }

            $plan->update(['status' => $status]);

            $results[$plan->id] = $plan->description;
        }

        return $results;
    }
}