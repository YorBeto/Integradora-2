<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TriggerLastRegisters
{
    use Dispatchable, SerializesModels;

    public function __construct()
    {
        //
    }
}
