<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class PirSensorUpdated implements ShouldBroadcast
{
    use Dispatchable;

    public $pirData;

    public function __construct($pirData)
    {
        $this->pirData = $pirData;
    }

    public function broadcastOn()
    {
        return new Channel('pir-sensor-updates');
    }

    public function broadcastAs()
    {
        return 'PirSensorUpdated';
    }

    public function broadcastWith()
    {
        return [
            'motion_detected' => $this->pirData->motion_detected,
            'event_date' => $this->pirData->event_date,
            'alert_message' => $this->pirData->alert_message ?? null,
            'alert_triggered' => $this->pirData->alert_triggered ?? false,
            'area_id' => $this->pirData->area_id ?? null,
            'area_name' => $this->pirData->area_name ?? 'Área desconocida',
            '_id' => $this->pirData->_id ?? null
        ];
    }
}