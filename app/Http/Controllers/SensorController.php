<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AlertLog;
use App\Models\Threshold;
use App\Models\User; // <-- Kailangan ito para mahanap natin ang mga Admin
use Filament\Notifications\Notification; // <-- Kailangan ito para sa Bell Pop-up!

class SensorController extends Controller
{
    public function store(Request $request)
    {
        // 1. Saluhin ang data galing sa ESP32
        $water_level = $request->input('water_level_cm');
        $rainfall = $request->input('rainfall_mm');
        $water_flow = $request->input('water_flow_rate');

        // 2. I-save sa 'readings' table
        DB::table('readings')->insert([
            'water_level_cm' => $water_level,
            'rainfall_mm' => $rainfall,
            'water_flow_rate' => $water_flow,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // ==========================================================
        // 🔴 ALERT LOGS LOGIC (SAMA-SAMA NA ANG 3 SENSORS!)
        // ==========================================================
        $settings = Threshold::first();

        if ($settings) {
            // A. HARDWARE ERROR DETECTION (Lahat ng sensors)
            if ($water_level < 0 || $rainfall < 0 || $water_flow < 0) {
                $this->logAlert('CRITICAL', 'System detected a hardware sensor error. Immediate maintenance required.');
            }
            // B. FLOOD DANGER (Kapag umabot sa limit ang TUBIG, ULAN, o AGOS)
            elseif ($water_level <= $settings->water_danger_cm || $rainfall >= $settings->rain_danger_mm || $water_flow >= 10.0) {
                $this->logAlert('CRITICAL', "DANGER THRESHOLD REACHED! Water: {$water_level}cm, Rain: {$rainfall}mm, Flow: {$water_flow}L/min. Evacuate immediately.");
            }
            // C. FLOOD WARNING (Kapag malapit na sa limit ang TUBIG, ULAN, o AGOS)
            elseif ($water_level <= $settings->water_warning_cm || $rainfall >= $settings->rain_warning_mm || $water_flow >= 5.0) {
                $this->logAlert('WARNING', "WARNING THRESHOLD REACHED. Water: {$water_level}cm, Rain: {$rainfall}mm, Flow: {$water_flow}L/min. Prepare for evacuation.");
            }
            // D. ALL CLEAR / NORMAL (Kapag safe na ang LAHAT ng sensors)
            else {
                $this->logAlert('NORMAL', "All sensors are at safe levels. Situation is back to normal.");
            }
        }
        // ==========================================================

        return response()->json(['message' => 'Sensor data saved successfully!'], 201);
    }

    public function latest()
    {
        $latestReading = DB::table('readings')->orderBy('id', 'desc')->first();
        $settings = DB::table('thresholds')->first();

        if ($latestReading && $settings) {
            $waterStatus = "NORMAL";
            if ($latestReading->water_level_cm <= $settings->water_danger_cm) {
                $waterStatus = "DANGER";
            } elseif ($latestReading->water_level_cm <= $settings->water_warning_cm) {
                $waterStatus = "WARNING";
            }

            $rainStatus = "NORMAL";
            if ($latestReading->rainfall_mm >= $settings->rain_danger_mm) {
                $rainStatus = "DANGER";
            } elseif ($latestReading->rainfall_mm >= $settings->rain_warning_mm) {
                $rainStatus = "WARNING";
            }

            $flowStatus = "NORMAL";
            if ($latestReading->water_flow_rate >= 10.0) {
                $flowStatus = "DANGER";
            } elseif ($latestReading->water_flow_rate >= 5.0) {
                $flowStatus = "WARNING";
            }

            $residentStatus = "NORMAL / SAFE";

            if ($waterStatus == "DANGER" && $flowStatus == "DANGER") {
                $residentStatus = "CRITICAL / EVACUATE NOW";
            } elseif ($waterStatus == "DANGER" && $flowStatus != "DANGER") {
                $residentStatus = "WARNING / PREPARE";
            } elseif ($rainStatus == "DANGER" || $rainStatus == "WARNING" || $flowStatus == "WARNING") {
                $residentStatus = "WARNING / PREPARE";
            } elseif ($waterStatus == "WARNING" && $rainStatus == "NORMAL" && $flowStatus == "NORMAL") {
                $residentStatus = "NORMAL / SAFE";
            }

            $latestReading->system_status = $residentStatus;

            $latestReading->sensor_latitude = $settings->sensor_latitude ?? 14.5398;
            $latestReading->sensor_longitude = $settings->sensor_longitude ?? 121.1340;
            $latestReading->hazard_radius = $settings->hazard_radius_meters ?? 500;
            
            return response()->json($latestReading, 200);
        }

        return response()->json(['message' => 'No data found'], 404);
    }

    /**
     * PRIVATE FUNCTION PARA SA LOGBOOK AT NOTIFICATION
     */
    /**
     * PRIVATE FUNCTION PARA SA LOGBOOK AT NOTIFICATION
     */
    /**
     * PRIVATE FUNCTION PARA SA LOGBOOK AT NOTIFICATION
     * Gamit ang Brute Force DB Injection para sapilitang tumunog ang bell!
     */
    private function logAlert(string $level, string $message)
    {
        $lastLog = \App\Models\AlertLog::orderBy('created_at', 'desc')->first();

        // Mag-save at mag-notify lang KUNG walang log o iba na yung level
        if (!$lastLog || $lastLog->level !== $level) {

            // 1. I-save sa Alert Logs Table natin
            \App\Models\AlertLog::create([
                'level' => $level,
                'message' => $message
            ]);

            // 2. KUNIN LAHAT NG USERS
            $users = \App\Models\User::all();

            // 3. BRUTE FORCE INSERT SA DATABASE (Walang kawala si Filament dito!)
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

                \Illuminate\Support\Facades\DB::table('notifications')->insert([
                    'id' => \Illuminate\Support\Str::uuid(),
                    'type' => 'Filament\Notifications\Database\Notifications\DatabaseNotification',
                    'notifiable_type' => get_class($user),
                    'notifiable_id' => $user->id,
                    'data' => json_encode([
                        'actions' => [],
                        'body' => $message,
                        'color' => $color,
                        'duration' => 'persistent', // Para hindi mawala hangga't di binabasa
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
