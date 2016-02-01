<?php namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SavingPlan extends Event
{
    use SerializesModels;

    public $planData;

    /**
     * SavingPlan constructor
     *
     * @param $planData
     */
    public function __construct($planData)
    {
        $this->planData = $planData;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}