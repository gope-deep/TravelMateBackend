<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Validator;

class PushNotificationController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function send(Request $request)
    {
            //  print_r("request1");
        // $request->validate([
        //     'token' => 'required|string',
        //     'title' => 'required|string',
        //     'body'  => 'required|string',
        // ]);
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            // 'token' => 'required|string',
            'title' => 'required|string',
            'body'  => 'required|string',
        ]);

         // check for validation errors
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $userdata= \App\Models\User::select('id', 'name', 'email','device_id')->where('id', '=',  $request->id)->get();
        $response = $this->firebase->sendNotification(
            $userdata[0]->device_id,
            $request->title,
            $request->body,
            ['click_action' => 'FLUTTER_NOTIFICATION_CLICK']
        );

        return response()->json(['user' => $response], 200);
    }
}
