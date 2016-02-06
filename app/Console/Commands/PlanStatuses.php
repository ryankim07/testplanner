<?php namespace App\Console\Commands;

/**
 * Class PlanStatuses
 *
 * Commands
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://tp.nophie.us)
 */

use Illuminate\Console\Command;

use App\Api\Jobs\PlansJobs;

use App\Facades\Tools;

class PlanStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plan_status:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * PlanStatuses constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(PlansJobs $job)
    {
        try {
            $results = $job->updatePlanStatuses();

            $this->info(implode(',', $results));
        } catch (\Exception $e) {
            $this->error('Failed to update plan status: ' . $e->getMessage());
        }
    }
}
