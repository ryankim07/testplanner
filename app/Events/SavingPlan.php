<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SavingPlan extends Event
{
    use SerializesModels;

    public $planData;

    /**
     * Create a new event instance.
     *
     * @return void
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
