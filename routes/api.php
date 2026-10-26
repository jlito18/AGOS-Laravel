<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Reading;
use App\Http\Controllers\Api\MobileAppController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\Api\ResidentController; // 🔴 DINAGDAG NATIN ITO
use App\Models\EvacuationCenter;

// =========================================================
// ITO YUNG TAMANG DAAN PARA SA ESP32 NATIN (May Alert Logs!)
// =========================================================
Route::post('/sensor/data', [SensorController::class, 'store']);
Route::get('/sensor/latest', [SensorController::class, 'latest']);

// =========================================================
// MGA ROUTES PARA SA ANDROID APP
// =========================================================
Route::post('/sensor-data', [MobileAppController::class, 'receiveSensorData']);
Route::get('/live-status', [MobileAppController::class, 'getLiveStatus']);

// 🔴 MGA BAGONG ROUTES PARA SA RESIDENTS
Route::get('/residents', [ResidentController::class, 'index']);
Route::post('/residents/register', [ResidentController::class, 'register']);

// =========================================================
// AUTHENTICATION & USERS
// =========================================================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/puroks', function () {
    return response()->json([
        'success' => true,
        'data' => \App\Models\Purok::pluck('name')
    ]);
});

Route::get('/chart-data', function () {
    $readings = \App\Models\Reading::orderBy('created_at', 'desc')
        ->take(10)
        ->get()
        ->reverse()
        ->values();

    return response()->json([
        'success' => true,
        'data' => $readings
    ]);
});

// 🔴 API PARA SA EVACUATION CENTERS SA MAPA NG ANDROID
Route::get('/evacuation-centers', function () {
    // Kukunin natin LAHAT basta may valid na coordinates
    $centers = \App\Models\EvacuationCenter::whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->get();

    return response()->json([
        'success' => true,
        'data' => $centers
    ]);
});

// EMERGENCY HOTLINES API
Route::get('/hotlines', function () {
    return response()->json([
        'success' => true,
        'data' => \App\Models\Hotline::all()
    ]);
});