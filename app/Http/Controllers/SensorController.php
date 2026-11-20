<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\AlertLog;
use App\Models\Threshold;
use App\Models\User;

class SensorController extends Controller
{
    public function store(Request $request)
    {
        $water_level = (float) $request->input('water_level_cm', 0);
        $rainfall = (float) $request->input('rainfall_mm', 0);
        $water_flow = (float) $request->input('water_flow_rate', 0);

        $settings = Threshold::first();
        $readingStatus = 'Safe';

        if ($settings) {
            $analysis = $this->evaluateSensors($water_level, $rainfall, $water_flow, $settings);
            $readingStatus = $analysis['reading_status'];

            $this->logAlert($analysis['alert_level'], $analysis['alert_message']);
        }

        // I-save sa 'readings' table kasama ang totoong status
        DB::table('readings')->insert([
            'water_level_cm' => $water_level,
            'rainfall_mm' => $rainfall,
            'water_flow_rate' => $water_flow,
            'status' => $readingStatus,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => 'Sensor data saved successfully!',
            'status' => $readingStatus,
        ], 201);
    }

    public function latest()
    {
        $latestReading = DB::table('readings')->orderBy('id', 'desc')->first();
        $settings = DB::table('thresholds')->first();

        if ($latestReading && $settings) {
            $analysis = $this->evaluateSensors(
                (float) $latestReading->water_level_cm,
                (float) $latestReading->rainfall_mm,
                (float) $latestReading->water_flow_rate,
                $settings
            );

            $latestReading->system_status = $analysis['resident_status'];
            $latestReading->sensor_latitude = $settings->sensor_latitude ?? 14.5398;
            $latestReading->sensor_longitude = $settings->sensor_longitude ?? 121.1340;
            $latestReading->hazard_radius = $settings->hazard_radius_meters ?? 500;

            return response()->json($latestReading, 200);
        }

        return response()->json(['message' => 'No data found'], 404);
    }

    /**
     * Centralized Multi-Sensor Cross-Verification Logic
     * (Tugma sa LatestReadingWidget at Android App)
     */
    private function evaluateSensors(float $water, float $rain, float $flow, object $settings): array
    {
        // 1. Individual Sensor Checks
        $waterStatus = 'NORMAL';
        if ($water < 0) {
            $waterStatus = 'ERROR';
        } elseif ($water <= $settings->water_danger_cm) {
            $waterStatus = 'DANGER';
        } elseif ($water <= $settings->water_warning_cm) {
            $waterStatus = 'WARNING';
        }

        $rainStatus = 'NORMAL';
        if ($rain < 0) {
            $rainStatus = 'ERROR';
        } elseif ($rain >= $settings->rain_danger_mm) {
            $rainStatus = 'DANGER';
        } elseif ($rain >= $settings->rain_warning_mm) {
            $rainStatus = 'WARNING';
        }

        $flowStatus = 'NORMAL';
        if ($flow < 0) {
            $flowStatus = 'ERROR';
        } elseif ($flow >= 10.0) {
            $flowStatus = 'DANGER';
        } elseif ($flow >= 5.0) {
            $flowStatus = 'WARNING';
        }

        // 2. Cross-Verification & Silent Monitoring
        if ($waterStatus === 'ERROR' || $rainStatus === 'ERROR' || $flowStatus === 'ERROR') {
            return [
                'reading_status' => 'Danger',
                'resident_status' => 'SYSTEM MAINTENANCE',
                'alert_level' => 'CRITICAL',
                'alert_message' => 'System detected a hardware sensor error. Immediate maintenance required.',
            ];
        }

        if ($waterStatus === 'DANGER' && $flowStatus === 'DANGER') {
            return [
                'reading_status' => 'Danger',
                'resident_status' => 'CRITICAL / EVACUATE NOW',
                'alert_level' => 'CRITICAL',
                'alert_message' => "VERIFIED FLOOD DANGER! Water: {$water}cm, Rain: {$rain}mm, Flow: {$flow}L/min. Evacuate immediately.",
            ];
        }

        if ($waterStatus === 'DANGER' && $flowStatus !== 'DANGER') {
            return [
                'reading_status' => 'Warning',
                'resident_status' => 'WARNING / PREPARE',
                'alert_level' => 'WARNING',
                'alert_message' => "UNVERIFIED WATER LEVEL (Check for Debris). Water: {$water}cm, Flow: {$flow}L/min.",
            ];
        }

        if ($rainStatus === 'DANGER' || $rainStatus === 'WARNING' || $flowStatus === 'WARNING' || $flowStatus === 'DANGER') {
            return [
                'reading_status' => 'Warning',
                'resident_status' => 'WARNING / PREPARE',
                'alert_level' => 'WARNING',
                'alert_message' => "FLOOD TREND DETECTED. Water: {$water}cm, Rain: {$rain}mm, Flow: {$flow}L/min. Prepare for evacuation.",
            ];
        }

        if ($waterStatus === 'WARNING' && $rainStatus === 'NORMAL' && $flowStatus === 'NORMAL') {
            return [
                'reading_status' => 'Safe',
                'resident_status' => 'NORMAL / SAFE',
                'alert_level' => 'NORMAL',
                'alert_message' => "Silent Monitoring: Water slightly elevated ({$water}cm) with normal rain and flow.",
            ];
        }

        return [
            'reading_status' => 'Safe',
            'resident_status' => 'NORMAL / SAFE',
            'alert_level' => 'NORMAL',
            'alert_message' => 'All sensors are at safe levels. Situation is back to normal.',
        ];
    }

    /**
     * Save AlertLog + Send Filament Bell Notification to Admin/Staff only
     */
    private function logAlert(string $level, string $message): void
    {
        $lastLog = AlertLog::orderBy('created_at', 'desc')->first();

        if (!$lastLog || $lastLog->level !== $level) {
            AlertLog::create([
                'level' => $level,
                'message' => $message,
            ]);

            // Admin at Staff lang dapat ang makakatanggap ng web dashboard bell
            $users = User::where('role', '!=', 'resident')->get();

            foreach ($users as $user) {
                $color = 'success';
                $icon = 'heroicon-o-check-circle';

                if ($level === 'CRITICAL') {
                    $color = 'danger';
                    $icon = 'heroicon-o-x-circle';
                } elseif ($level === 'WARNING') {
                    $color = 'warning';
                    $icon = 'heroicon-o-exclamation-triangle';
                }

                DB::table('notifications')->insert([
                    'id' => Str::uuid(),
                    'type' => 'Filament\Notifications\Database\Notifications\DatabaseNotification',
                    'notifiable_type' => get_class($user),
                    'notifiable_id' => $user->id,
                    'data' => json_encode([
                        'actions' => [],
                        'body' => $message,
                        'color' => $color,
                        'duration' => 'persistent',
                        'icon' => $icon,
                        'iconColor' => $color,
                        'status' => $color,
                        'title' => 'A.G.O.S. ' . $level . ' ALERT',
                        'view' => 'filament-notifications::notification',
                        'viewData' => [],
                        'format' => 'filament',
                    ]),
                    'read_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
