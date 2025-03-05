<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worker;
use App\Models\Person;
use Illuminate\Support\Facades\DB;


class WorkerController extends Controller
{
    public function index()
    {
        $workers = DB::table('workers')
            ->join('people', 'workers.person_id', '=', 'people.id')
            ->select(
                'workers.id',
                'people.name',
                'people.last_name',
                'people.birth_date',
                DB::raw('TIMESTAMPDIFF(YEAR, people.birth_date, CURDATE()) as age'),
                'people.phone',
                'workers.RFID',
                'workers.RFC',
                'workers.NSS'
            )
            ->get();

        return response()->json($workers);
    }
}
