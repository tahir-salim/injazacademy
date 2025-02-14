<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
            'device_type' => 'required',
            'fcm_token' => '',
            'device_id' => '',
            'app_version' => '',
        ]);
        if ($validate->fails()) {
            return $this->formatResponse(
                'error',
                'validation-error',
                $validate->errors()->first()
            );
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->formatResponse(
                'error',
                'Email-address-not-found'
            );
        }

        if (!Hash::check($request->password, $user->password)) {
            return $this->formatResponse(
                'error',
                'credentials-do-not-match'
            );
        }

        if ($user->is_blocked) {
            return $this->formatResponse(
                'error',
                'user-blocked'
            );
        }


        // first you need to make sure the password is correct before updating data
        $user->device_type = $request->device_type;
        if (isset($request->fcm_token)) {
            $user->fcm_token = $request->fcm_token;
        }
        if ($request->app_version) {
            $user->app_version = $request->app_version;
        }
        if ($request->device_id) {
            $user->device_id = $request->device_id;
        }

        $user->last_activity_at = now();
        $user->save();
        $token = $user->createToken($request->device_id ?: $request->device_type)->plainTextToken;

        $user->api_token = $token;

        return $this->formatResponse(
            'success',
            'login-successfully',
            $user
        )->withHeaders([
            'x-auth-token' => $token,
        ]);
    }
    public function logout(Request $request)
    {
        if ($request->user()->currentAccessToken()->delete()) {
            return $this->formatResponse('success', 'logout-successfully');
        }
        abort(401);
    }
}
