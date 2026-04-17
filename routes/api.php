<?php
use App\Models\Devices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/monitors', function () {
    return Devices::all()->append('has_power');
});

Route::post('/heartbeat', function (Request $request) {
    $data = $request->validate([
        'device_name' => 'required|string',
        'mac_address' => 'required|string',
    ]);

    $device = Devices::updateOrCreate(
        ['mac_address' => $data['mac_address']],
        ['device_name' => $data['device_name'], 'last_seen_at' => now(), 'is_online' => true]
    );
    broadcast(new \App\Events\UpdateDeviceStatus($device));
    return response()->json(['message' => 'Heartbeat received', 'device' => $device], 200);
});