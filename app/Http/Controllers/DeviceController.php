<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index()
    {
        $devices = Device::all();
        return response()->json($devices);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required',
            'reading_time' => 'required',
            'response_time' => 'required',
        ]);

        $device = Device::create($request->all());

        return response()->json(['success' => 'Device created successfully.', 'device' => $device]);
    }

    public function show(Device $device)
    {
        return response()->json($device);
    }

    public function update(Request $request, Device $device)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required',
            'reading_time' => 'required',
            'response_time' => 'required',
        ]);

        $device->update($request->all());

        return response()->json(['success' => 'Device updated successfully.', 'device' => $device]);
    }

    public function destroy(Device $device)
    {
        $device->delete();
        return response()->json(['success' => 'Device deleted successfully.']);
    }
}
