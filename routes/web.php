<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB; // <-- Idinagdag natin ito para maka-bilang sa database

Route::get('/', function () {
    return view('welcome');
});

// ITO YUNG BAGONG ULTIMATE DEBUGGER NATIN
Route::get('/test-bell', function () {
    // 1. Kunin kung sino ang naka-login na admin ngayon
    $user = \Illuminate\Support\Facades\Auth::user();

    // 2. Kapag walang na-detect na naka-login
    if (!$user) {
        return "ERROR: Hindi ka naka-login! Mag-login ka muna sa admin dashboard, tapos i-open mo ulit itong /test-bell sa bagong tab.";
    }

    // 3. Ipadala ang notification nang DIREKTA sa account mo lang
    Notification::make()
        ->title('TEST MULA SA BROWSER 🔔')
        ->body('Kung nakikita mo ito, gumagana ang bell!')
        ->success()
        ->sendToDatabase($user);

    // 4. Bilangin kung ilan ang nasa database para sure!
    $totalInDb = DB::table('notifications')->count();
    $unreadForYou = $user->unreadNotifications->count();

    return "
        <h3>✅ Notification Sent!</h3>
        <p>Ipinadala kay: <b>{$user->name}</b></p>
        <p>Total na Notifications sa buong System: <b>{$totalInDb}</b></p>
        <p>Unread Notifications para sa account mo: <b><span style='color:red;'>{$unreadForYou}</span></b></p>
        <br>
        <p>👉 Kung ang 'Unread' ay 1 o pataas, bumalik ka sa dashboard at i-hard refresh (CTRL + F5). Siguradong nandun na 'yon!</p>
    ";
});
