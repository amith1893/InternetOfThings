<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthenticationController extends Controller
{
    public function postCompare(Request $request)
    {
        $now = Carbon::now();
        // Create a face detected event for logging purposes
        $event = Event::create([
            'snapshot_id' => null,
            'type' => 'authentication-attempt',
            'icon' => 'fa fa-lock',
            'message' => 'Eagle Eye Authentication Requested - Not Implemented - '.$now->toDateTimeString()
        ]);
        return response()->json(['message' => 'Pending implementation']);

    }
}
