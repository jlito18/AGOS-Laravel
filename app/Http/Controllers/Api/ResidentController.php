<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Resident;

class ResidentController extends Controller
{
    // Kukunin ng Android app ang listahan ng residents para sa notifications
    public function index()
    {
        $residents = Resident::all();
        return response()->json([
            'status' => 'success',
            'data' => $residents
        ]);
    }

    // Gagamitin ng Android app para makapag-register ang bagong resident
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
        ]);

        $resident = Resident::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Resident registered successfully!',
            'data' => $resident
        ]);
    }
}
