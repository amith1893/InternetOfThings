<?php

namespace App\Http\Controllers\API;

use App\Device;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IoTController extends Controller
{
    public function postRegister(Request $request)
    {
        $device = Device::create([
            'device_ip' => $request->device_ip,
            'port' => $request->device_port,
            'description' => $request->description,
        ]);
        return response()->json(['status' => 'success', 'message'=> 'This device has been registered successfully']);
    }

    public function sendLightRequest()
    {

    }

    public function sendMusicRequest()
    {

    }

}
