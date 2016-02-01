<?php namespace App\Listeners;

/**
 * Class SendNotification
 *
 * Listener
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://tp.nophie.us)
 */

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Api\ActivityStreamApi,
    App\Api\EmailApi;

class SendNotification
{
    /**
     * @var ActivityStreamApi
     */
    protected $asApi;

    /**
     * @var EmailApi
     */
    protected $emailApi;

    /**
     * SendNotification constructor
     *
     * @param ActivityStreamApi $asApi
     * @param EmailApi $emailApi
     */
    public function __construct(ActivityStreamApi $asApi, EmailApi $emailApi)
    {
        $this->asApi    = $asApi;
        $this->emailApi = $emailApi;
    }

    /**
     * Handle the event when saving plan
     *
     * @param SavingPlan $event
     */
    public function onSavingPlan($event)
    {
        $data = $event->planData;

        // Log activity stream
        $this->asApi->saveActivityStream($data);

        // Mail all test browsers
        $this->emailApi->sendEmail('plan-new', $data);
    }

    /**
     * Handle the event when updating plan
     *
     * @param $event
     */
    public function onUpdatingPlan($event)
    {
        $data = $event->planData;

        // Log activity stream
        $this->asApi->saveActivityStream($data);

        // Mail all test browsers
        // Mail all test browsers
        $this->emailApi->sendEmail('plan-updated', $data);
    }

    /**
     * Handle the event when responding plan
     *
     * @param $event
     */
    public function onRespondingPlan($event)
    {
        $data = $event->planData;

        // Log activity stream
        $this->asApi->saveActivityStream($data);

        // Mail all test browsers
        $this->emailApi->sendEmail('ticket-response', $data);
    }

    /**
     * Event subscribers
     *
     * @param $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\SavingPlan',
            'App\Listeners\SendNotification@onSavingPlan'
        );

        $events->listen(
            'App\Events\UpdatingPlan',
            'App\Listeners\SendNotification@onUpdatingPlan'
        );

        $events->listen(
            'App\Events\RespondingPlan',
            'App\Listeners\SendNotification@onRespondingPlan'
        );
    }
}