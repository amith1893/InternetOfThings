<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $snaps = \App\Models\Snapshot::orderBy('created_at','desc')->get();
    return view('welcome', ['snaps' => $snaps]);
});

Route::post('/', 'API\ImageController@postProcessImageWeb');


Route::get('app', function () {
    $light = \App\Models\Setting::where('name', 'light_status')->first();
    $lightVal = json_decode($light->value);

    $music = \App\Models\Setting::where('name', 'music_status')->first();
    $musicVal = json_decode($music->value);

    return view('app',['light'=> $lightVal->value,'music'=> $musicVal->value]);
});

Route::get('app/events', function () {
    $events = \App\Models\Event::orderBy('created_at','desc')->get();
    return view('event-log', ['events'=>$events]);
});


Route::get('setting/light', function () {
    $light = \App\Models\Setting::where('name', 'light_status')->first();
    $lightVal = json_decode($light->value);

    $newLightVal = !($lightVal->value);
    $light_setting = collect(['value'=> $newLightVal]);
    $light->update(['value' => $light_setting->toJson()]);
    flash('Light Setting Changed!');
    return redirect('app');
});

Route::get('setting/music', function () {
    $light = \App\Models\Setting::where('name', 'music_status')->first();
    $lightVal = json_decode($light->value);

    $newLightVal = !($lightVal->value);
    $light_setting = collect(['value'=> $newLightVal]);
    $light->update(['value' => $light_setting->toJson()]);
    flash('Music Setting Changed!');
    return redirect('app');
});


Route::get('setup-database', 'API\SystemController@createSettings');
Route::get('polling', 'API\SystemController@getSettings');