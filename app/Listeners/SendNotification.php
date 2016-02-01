<?php namespace App\Listeners;


use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Events\SavingPlan;

use App\Facades\Email;

use App\Api\ActivityStreamApi;

class SendNotification
{
    protected $asApi;

    /**
     * SendNotification constructor
     *
     * @param ActivityStreamApi $asApi
     */
    public function __construct(ActivityStreamApi $asApi)
    {
        $this->asApi = $asApi;
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
        Email::sendEmail('plan-new', $data);
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
        Email::sendEmail('plan-updated', $data);
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
        Email::sendEmail('ticket-response', $data);
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