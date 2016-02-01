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
     * Handle the event
     *
     * @param SavingPlan $event
     */
    public function handle(SavingPlan $event)
    {
        $data = $event->planData;

        // Log activity stream
        $this->asApi->saveActivityStream($data);

        // Mail all test browsers
        Email::sendEmail('plan-created', array_merge($data, ['testers' => $data['testers']]));
    }
}
