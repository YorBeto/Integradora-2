<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rfid;
use App\Models\Worker;

class RfidSensorController extends Controller
{
    public function getAll()
    {
        return Rfid::all();
    }

    public function getAllRfidCodes()
    {
        return Rfid::pluck('rfid_code');
    }

    public function getAssignedRfidCodes()
    {
        $assignedCodes = Worker::pluck('RFID')->filter()->toArray();
        return response()->json($assignedCodes);
    }
}
