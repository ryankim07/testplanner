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
     * @var array
     */
    protected $definedStatus = [
        'new'      => 0,
        'progress' => 0,
        'update'   => 1,
        'complete' => 1
    ];

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
     * Observer to change status of a single plan
     * according to all user's responses
     *
     * @param $planId
     * @return mixed
     */
    public function updatePlanStatus($planId)
    {
        if (isset($planId)) {
            $plan    = $this->model->find($planId);
            $testers = $plan->testers()->get();
            $results = $this->getTestersTicketsStatus($plan, $testers, 'single');
        } else {
            $plans = $this->model->all();

            foreach ($plans as $plan) {
                $testers = $plan->testers()->get();
                $results[] = $this->getTestersTicketsStatus($plan, $testers);
            }
        }

        return $results;
    }

    /**
     * Get overall ticket status for each tester
     *
     * @param $plan
     * @param $testers
     * @param null $model
     * @return mixed
     */
    public function getTestersTicketsStatus($plan, $testers, $model = null)
    {
        $totalBrowsers = 0;
        $overallStatus = 'new';
        $allStatus     = [];

        foreach ($testers as $eachTester) {
            // Total of assigned browsers
            $totalBrowsersToTest = count(explode(',', $eachTester->browsers));

            // Overall count of all browsers for each tester
            $totalBrowsers += $totalBrowsersToTest;

            // Responses and total
            $responses = $eachTester->tickets()->where('plan_id', '=', $plan->id)->get();
            $totalResponses = count($responses);

            // If total responses already doesn't match, no need to continue further,
            // status is still new
            if ($totalResponses != $totalBrowsersToTest) {
                $allStatus[] = 'new';
                continue;
            }

            // Don't need to check each status, just get the last outcome
            foreach ($responses as $ticket) {
                $allStatus[] = $ticket->status;
            }
        }

        // Determine plan status
        $allComplete = $this->in_array_all('complete', $allStatus);
        $new         = in_array('new', $allStatus);
        $complete    = in_array('complete', $allStatus);
        $progress    = in_array('progress', $allStatus);
        $update      = in_array('update', $allStatus);

        if ($complete && $update) {
            $overallStatus = 'update';
        } elseif ($update) {
            $overallStatus = 'update';
        } elseif ($allComplete && $totalBrowsers == count($allStatus)) {
            $overallStatus = 'complete';
        } else {
            $overallStatus = 'progress';
        }

        $plan->update(['status' => $overallStatus]);

        if ($model == 'single') {
            $results['status'] = $overallStatus;
        } else {
            $results = $plan->description;
        }
        return $results;
    }

    /**
     * Check if all the array values contains the same chosen value
     *
     * @param $value
     * @param $array
     * @return bool
     */
    public function in_array_all($value, $array)
    {
        return (reset($array) == $value && count(array_unique($array)) == 1);
    }
}