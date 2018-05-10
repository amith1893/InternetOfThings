<?php

namespace App\Http\Controllers\API;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SystemController extends Controller
{
    public function getSystemInfo()
    {
        return response()->json([
            'name' => 'Green Eye - Backend API',
            'author' => 'Guillermo Rodriguez',
            'version' => '0.1',
        ]);
    }

    public function getSettings()
    {

        $light = Setting::where('name', 'light_status')->first();
        $lightVal = json_decode($light->value);

        $music = Setting::where('name', 'music_status')->first();
        $musicVal = json_decode($music->value);


        return response()->json([
            'light' => $lightVal->value,
            'light_updated_at' => $light->updated_at,
            'music' => $musicVal->value,
            'music_updated_at' => $music->updated_at,
        ]);
    }

    public function createSettings()
    {
        $light_setting = collect(['value'=> false]);
        Setting::create(['name'=>'light_status','value' => $light_setting->toJson()]);
        Setting::create(['name'=>'music_status','value' => $light_setting->toJson()]);
        return response()->json([
            'message' => 'Database has been provisioned',
        ]);
    }

}
