<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// System
Route::get('system-info', 'API\SystemController@getSystemInfo');

// Image
Route::post('image/process', 'API\ImageController@postProcessImage');

// Authentication
Route::post('authentication/compare', 'API\AuthenticationController@postCompare');

// IoT
Route::post('iot/register', 'API\IoTController@postRegister');

Route::get('setting/light', function () {
    $light = \App\Models\Setting::where('name', 'light_status')->first();
    $lightVal = json_decode($light->value);

    $newLightVal = !($lightVal->value);
    $light_setting = collect(['value'=> $newLightVal]);
    $light->update(['value' => $light_setting->toJson()]);
    return response()->json(['message' => 'Light Setting Toggled']);
});

Route::get('setting/music', function () {
    $light = \App\Models\Setting::where('name', 'music_status')->first();
    $lightVal = json_decode($light->value);

    $newLightVal = !($lightVal->value);
    $light_setting = collect(['value'=> $newLightVal]);
    $light->update(['value' => $light_setting->toJson()]);
    return response()->json(['message' => 'Music Setting Toggled']);
});
