<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class ThSensorUpdated implements ShouldBroadcast
{
    use Dispatchable;

    public $thData;

    public function __construct($thData)
    {
        $this->thData = $thData;
    }

    public function broadcastOn()
    {
        return new Channel('th-sensor-updates');
    }

    public function broadcastAs()
    {
        return 'ThSensorUpdated';
    }

    // Añade este método para definir explícitamente la estructura de datos
    public function broadcastWith()
    {
        return [
            'temperature_c' => $this->thData->temperature_c,
            'humidity_percent' => $this->thData->humidity_percent,
            'event_date' => $this->thData->event_date,
            '_id' => $this->thData->_id ?? null
        ];
    }
}