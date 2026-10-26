<?php

namespace App\Filament\Widgets;

use App\Models\EvacuationCenter;
use App\Models\Threshold;
use App\Models\Reading; // Wag kalimutan 'to!
use Filament\Widgets\Widget;

class HazardMapWidget extends Widget
{
    protected static string $view = 'filament.widgets.hazard-map-widget';
    protected int | string | array $columnSpan = '3';
    protected static ?int $sort = 2;

    protected function getViewData(): array
    {
        $settings = Threshold::first();
        $latestReading = Reading::latest()->first(); // Kinukuha natin yung live data

        return [
            'evacCenters' => EvacuationCenter::all(),
            'sensorLat' => $settings->sensor_latitude ?? '14.5641',
            'sensorLng' => $settings->sensor_longitude ?? '121.1394',

            // 🔴 IPAPASA NATIN YUNG RADIUS PAPUNTA SA BLADE VIEW
            'hazardRadius' => $settings ? $settings->hazard_radius_meters : 500,

            'currentWaterLevel' => $latestReading ? $latestReading->water_level_cm : 0,
            'warningLevel' => $settings ? $settings->water_warning_cm : 100,
            'dangerLevel' => $settings ? $settings->water_danger_cm : 200,
        ];
    }
}
