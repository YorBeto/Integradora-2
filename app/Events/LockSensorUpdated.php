<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use App\Models\Rfid;
use App\Models\LockSensor;
class LockSensorUpdated implements ShouldBroadcast
{
    use Dispatchable;

    public $lockData;

    public function __construct($lockData)
    {
        $this->lockData = $lockData;
    }

    public function broadcastOn()
    {
        return new Channel('lock-sensor-updates');
    }

    public function broadcastAs()
    {
        return 'LockSensorUpdated';
    }

    public function broadcastWith()
    {
        return [
            'rfid_code' => $this->lockData->rfid_code,
            'date' => $this->lockData->date,
            'origin' => $this->lockData->origin,
            'status' => $this->lockData->status,
            'persona' => $this->getPersonaInfo($this->lockData->rfid_code)
        ];
    }

    protected function getPersonaInfo($rfidCode)
    {
        $persona = Rfid::where('rfid_code', $rfidCode)->first();
        
        return $persona ? [
            'nombre' => $persona->name ?? 'Desconocido',
            'puesto' => $persona->position ?? 'No definido',
            'area' => $persona->area ?? 'No definida'
        ] : null;
    }
}