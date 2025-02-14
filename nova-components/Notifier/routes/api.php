<?php

use App\Libraries\PushNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

Route::post('/send', function (Request $request) {

    $request->validate([
        'title' => 'required',
        'body' => 'required',
    ]);

    PushNotification::Pusher(
        $request->title,
        $request->body,
        null,
        null,
        null,
        false,
        'AdminNotification'
    );

    return response()->json([
        'status'  => 'success',
        'message' => 'Test Created',
        'data'    => '',
    ], 200);
});
