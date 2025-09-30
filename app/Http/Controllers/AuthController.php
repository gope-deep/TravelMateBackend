<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/* * AuthController
 * 
 * This controller handles user authentication including login.
 * 
 * Middleware: None (login is public)
 * 
 * Methods:
 * - login(Request $request): Authenticates user and returns an API token.
*/

class AuthController extends Controller
{
    // login function
    public function login(Request $request){
       // print_r("request". $request);
        // validate incoming request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // check for validation errors
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //$newpassword = Hash::make('test');
      //  print_r($newpassword);
        // check user credentials
        $user = User::where('email', $request->email)->first();

        // if user not found or password does not match
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'The provided credentials are incorrect.'], 401);
        }

        // updating device id IOS/Android
        if(!empty($request->device_id)){
           $updateUserRes =  User::where('id', $user->id)
            ->update(['device_id' => $request->device_id]);
            if($updateUserRes){
                $user->device_id = $request->device_id;
            }
        }

        // deleting previous tokens 
        $user->tokens()->delete();

        // create token
        $token = $user->createToken('api-token')->plainTextToken;
         
        // return response with user and token
        return response()->json(['user' => $user, 'token' => $token], 200);
    }
}