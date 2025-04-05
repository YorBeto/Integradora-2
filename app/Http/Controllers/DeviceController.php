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
        $validatedData = $request->validate([
            'name' => 'required',
            'password' => 'required',
            'area_id' => 'required|integer',
            'reading_time' => 'required',
            'response_time' => 'required',
        ]);

        $device = Device::create($validatedData);

        return response()->json(['success' => 'Device created successfully.', 'device' => $device]);
    }

    public function show($id)
    {
        $device = Device::find($id);

        if (!$device) {
            return response()->json(['error' => 'Dispositivo no encontrado.'], 404);
        }

        return response()->json($device, 200);
    }

    public function update(Request $request, Device $device)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'password' => 'required',
            'area_id' => 'required|integer',
            'reading_time' => 'required',
            'response_time' => 'required',
        ]);

        $device->update($validatedData);

        return response()->json(['success' => 'Device updated successfully.', 'device' => $device]);
    }

    public function destroy(Device $device)
    {
        $device->delete();
        return response()->json(['success' => 'Device deleted successfully.']);
    }
}