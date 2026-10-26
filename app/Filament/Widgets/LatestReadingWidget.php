<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\HtmlString;

class LatestReadingWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '5s';

    // 1. Kakain siya ng 2 out of 5 parts ng screen
    protected int | string | array $columnSpan = 2;

    // 2. Gagawin nating 2 columns sa loob para magkatabi yung mga cards!
    protected function getColumns(): int
    {
        return 2;
    }

    // ... (tuloy sa getStats() function mo)
    protected function getStats(): array
    {
        $latestReading = DB::table('readings')->orderBy('id', 'desc')->first();
        $settings = DB::table('thresholds')->first();

        if (!$latestReading || !$settings) {
            return [
                Stat::make('System Status', 'No Data')->description('Waiting for sensor data...'),
            ];
        }

        // ==========================================
        // 0. ESP32 CONNECTION CHECKER (Heartbeat)
        // ==========================================
        $isOnline = false;
        $lastUpdate = Carbon::parse($latestReading->created_at);
        if ($lastUpdate->diffInSeconds(now()) <= 10) {
            $isOnline = true;
        }

        if ($isOnline) {
            $espStatus = '<span style="color: #10B981; font-weight: bold;">ESP32 Online</span>';
        } else {
            $espStatus = '<span style="color: #EF4444; font-weight: bold;">ESP32 Offline</span>';
        }

        $sensorErrorHtml = '<span style="color: #EF4444; font-weight: bold;">🔴 SENSOR ERROR</span>';

        // ==========================================
        // 1. INDIVIDUAL SENSOR FAULT DETECTION
        // ==========================================

        // --- WATER LEVEL ---
        $waterStatus = "NORMAL";
        $waterColor = "success";
        if ($latestReading->water_level_cm < 0) {
            // KAPAG NAG-SEND ANG ESP32 NG NEGATIVE VALUE
            $waterStatus = "ERROR";
            $waterColor = "danger";
        } elseif ($latestReading->water_level_cm <= $settings->water_danger_cm) {
            $waterStatus = "DANGER";
            $waterColor = "danger";
        } elseif ($latestReading->water_level_cm <= $settings->water_warning_cm) {
            $waterStatus = "WARNING";
            $waterColor = "warning";
        }

        // --- RAINFALL ---
        $rainStatus = "NORMAL";
        $rainColor = "success";
        if ($latestReading->rainfall_mm < 0) {
            $rainStatus = "ERROR";
            $rainColor = "danger";
        } elseif ($latestReading->rainfall_mm >= $settings->rain_danger_mm) {
            $rainStatus = "DANGER";
            $rainColor = "danger";
        } elseif ($latestReading->rainfall_mm >= $settings->rain_warning_mm) {
            $rainStatus = "WARNING";
            $rainColor = "warning";
        }

        // --- WATER FLOW ---
        $flowStatus = "NORMAL";
        $flowColor = "success";
        if ($latestReading->water_flow_rate < 0) {
            $flowStatus = "ERROR";
            $flowColor = "danger";
        } elseif ($latestReading->water_flow_rate >= 10.0) {
            $flowStatus = "DANGER";
            $flowColor = "danger";
        } elseif ($latestReading->water_flow_rate >= 5.0) {
            $flowStatus = "WARNING";
            $flowColor = "warning";
        }

        // ==========================================
        // 2. CROSS-VERIFICATION & SILENT MONITORING 
        // ==========================================
        $lguOverallStatus = "NORMAL";
        $lguOverallColor = "success";
        $residentStatus = "NORMAL / SAFE";
        $residentColor = "success";

        if ($waterStatus == "ERROR" || $rainStatus == "ERROR" || $flowStatus == "ERROR") {
            $lguOverallStatus = "MAINTENANCE REQUIRED (Sensor Fault)";
            $lguOverallColor = "danger";
            $residentStatus = "SYSTEM MAINTENANCE";
            $residentColor = "warning";
        } elseif ($waterStatus == "DANGER" && $flowStatus == "DANGER") {
            $lguOverallStatus = "CRITICAL (Verified by Flow)";
            $lguOverallColor = "danger";
            $residentStatus = "CRITICAL / EVACUATE NOW";
            $residentColor = "danger";
        } elseif ($waterStatus == "DANGER" && $flowStatus != "DANGER") {
            $lguOverallStatus = "UNVERIFIED (Check for Debris)";
            $lguOverallColor = "warning";
            $residentStatus = "WARNING / PREPARE";
            $residentColor = "warning";
        } elseif ($rainStatus == "DANGER" || $rainStatus == "WARNING" || $flowStatus == "WARNING" || $flowStatus == "DANGER") {
            $lguOverallStatus = "WARNING (Trend Detected)";
            $lguOverallColor = "warning";
            $residentStatus = "WARNING / PREPARE";
            $residentColor = "warning";
        } elseif ($waterStatus == "WARNING" && $rainStatus == "NORMAL" && $flowStatus == "NORMAL") {
            $lguOverallStatus = "MONITORING (Water Slightly High)";
            $lguOverallColor = "warning";
            $residentStatus = "NORMAL / SAFE";
            $residentColor = "success";
        }

        // ==========================================
        // 3. PUSH TO FILAMENT DASHBOARD
        // ==========================================
        return [
            Stat::make('🌊 Water Level', $latestReading->water_level_cm < 0 ? 'ERROR' : $latestReading->water_level_cm . ' cm')
                ->description(new HtmlString("Status: " . ($waterStatus == 'ERROR' ? $sensorErrorHtml : "{$waterStatus} | {$espStatus}")))
                ->color($waterColor),

            Stat::make('🌧️ Rainfall', $latestReading->rainfall_mm < 0 ? 'ERROR' : $latestReading->rainfall_mm . ' mm')
                ->description(new HtmlString("Status: " . ($rainStatus == 'ERROR' ? $sensorErrorHtml : "{$rainStatus} | {$espStatus}")))
                ->color($rainColor),

            Stat::make('💧 Water Flow', $latestReading->water_flow_rate < 0 ? 'ERROR' : $latestReading->water_flow_rate . ' L/min')
                ->description(new HtmlString("Status: " . ($flowStatus == 'ERROR' ? $sensorErrorHtml : "{$flowStatus} | {$espStatus}")))
                ->color($flowColor),

            Stat::make('🏛️ LGU Overall Status', $lguOverallStatus)
                ->description('Internal Command Center Analysis')
                ->color($lguOverallColor),

            Stat::make('📱 Resident App Status', $residentStatus)
                ->description('Current threat level broadcasted to mobile users')
                ->color($residentColor),
        ];
    }
}
