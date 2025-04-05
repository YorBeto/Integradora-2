<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use App\Models\Area;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class DeviceController extends Controller
{
    public function index()
    {
        $devices = Device::with('area')->get()->map(function ($device) {
            return [
                'id' => $device->id,
                'name' => $device->name,
                'password' => $device->password,
                'area_id' => $device->area_id,
                'area_name' => $device->area->name ?? 'Sin área',
                'reading_time' => $device->reading_time,
                'response_time' => $device->response_time
            ];
        });
        
        return response()->json($devices);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required',
            'area_id' => 'required|exists:areas,id',
            'reading_time' => 'required|integer',
            'response_time' => 'required|integer',
        ]);

        $device = Device::create($request->all());

        return response()->json(['success' => 'Device created successfully.', 'device' => $device]);
    }

    public function show(Device $device)
    {
        return response()->json($device);
    }

            public function update(Request $request, $id)
        {
            Log::info('Update request received:', ['id' => $id, 'data' => $request->all()]);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|string',
                'password' => 'sometimes|string',
                'area_id' => 'sometimes|integer|exists:areas,id',
                'reading_time' => 'sometimes|integer',
                'response_time' => 'sometimes|integer',
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], 422);
            }

            $device = Device::find($id);

            if (!$device) {
                Log::error('Device not found:', ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Device not found'
                ], 404);
            }

            $device->fill($request->only([
                'name', 
                'password', 
                'area_id', 
                'reading_time', 
                'response_time'
            ]));

            if ($device->isDirty()) {
                $device->save();
                Log::info('Device updated successfully:', $device->toArray());
                
                return response()->json([
                    'success' => true,
                    'message' => 'Device updated successfully',
                    'device' => $device
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'No changes detected',
                'device' => $device
            ]);
        }

    public function destroy(Device $device)
    {
        $device->delete();
        return response()->json(['success' => 'Device deleted successfully.']);
    }

    public function getAreas()
    {
        $areas = Area::select('id', 'name')->get();
        return response()->json($areas);
    }
}