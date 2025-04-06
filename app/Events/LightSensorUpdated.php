<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class LightSensorUpdated implements ShouldBroadcast
{
    use Dispatchable;

    public $lightData;

    public function __construct($lightData)
    {
        $this->lightData = $lightData;
    }

    public function broadcastOn()
    {
        return new Channel('light-sensor-updates');
    }

    public function broadcastAs()
    {
        return 'LightSensorUpdated';
    }
}