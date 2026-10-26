<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EvacuationCenter;
use App\Models\Reading;
use App\Models\Threshold;
use Illuminate\Http\Request;

class MobileAppController extends Controller
{
    public function getLiveStatus()
    {
        $reading = Reading::latest()->first();
        $settings = Threshold::first();
        $evacCenters = EvacuationCenter::all();

        $waterLevel = $reading ? (float) $reading->water_level_cm : 0;
        $warning = $settings ? (float) $settings->water_warning_cm : 100;
        $danger = $settings ? (float) $settings->water_danger_cm : 200;

        $status = 'Normal';
        if ($waterLevel <= $danger) { // Inayos natin: pababa ang sukat
            $status = 'Danger';
        } elseif ($waterLevel <= $warning) {
            $status = 'Warning';
        }

        return response()->json([
            'success' => true,
            'data' => [
                'current_water_level' => $waterLevel,
                'alert_status' => $status,
                'last_update' => $reading ? $reading->created_at->format('M d, Y h:i A') : 'No Data',
                'evacuation_centers' => $evacCenters
            ]
        ]);
    }

    // ITO ANG TATAWAGIN NG ESP32 HARDWARE NIYO
    public function receiveSensorData(Request $request)
    {
        $request->validate([
            'water_level_cm' => 'required|numeric',
            'water_flow_rate' => 'nullable|numeric', // Fixed naming
            'rainfall_mm' => 'nullable|numeric',
        ]);

        $reading = Reading::create([
            'water_level_cm' => $request->water_level_cm,
            'water_flow_rate' => $request->water_flow_rate ?? 0, // Fixed naming
            'rainfall_mm' => $request->rainfall_mm ?? 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sensor data received and saved!',
            'data' => $reading
        ]);
    }
}
