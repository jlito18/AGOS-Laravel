<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // FUNCTION PARA SA REGISTER
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'contact_number' => 'required|string',
            'barangay' => 'required|string'
        ]);

        // 1. Gawa account para makapag-login sa app
        $user = User::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'resident'
        ]);

        // 2. I-save din sa Residents table para makatanggap ng SMS Alerts!
        \App\Models\Resident::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'contact_number' => $request->contact_number,
            'barangay' => $request->barangay,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Account created successfully',
            'user' => $user
        ]);
    }

    // FUNCTION PARA SA LOGIN
    // FUNCTION PARA SA LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Kunin ang barangay/purok mula sa residents table gamit ang contact number
            $contactNumber = str_replace('@agos.lgu', '', $user->email);
            $resident = \App\Models\Resident::where('contact_number', $contactNumber)->first();
            $user->barangay = $resident ? $resident->barangay : 'Taytay, Rizal';

            $token = $user->createToken('AgosMobileApp')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Login successful',
                'user' => $user,
                'token' => $token
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid credentials'
        ], 401);
    }
}
