<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class WeightSensorUpdated implements ShouldBroadcast
{
    use Dispatchable;

    public $weightData;

    public function __construct($weightData)
    {
        $this->weightData = $weightData;
    }

    public function broadcastOn()
    {
        return new Channel('weight-sensor-updates');
    }

    public function broadcastAs()
    {
        return 'WeightSensorUpdated';
    }
}
