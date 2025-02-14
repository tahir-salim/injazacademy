<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\AccountCreated;
use App\Mail\VerificationEmail;
use App\Models\Role;
use App\Models\User;
use App\Models\UserVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{

    public function signUpRequest(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'first_name' => 'required|string',
            'middle_name' => 'string',
            'last_name' => 'required|string',
            'ar_name' => 'required|string',
            'user_name' => 'string',
            'password' => 'required|min:6',
            'country_id' => 'required',
            'cpr' => 'required|min:5|max:10',
            'dob' => 'required|date',
            'gender' => 'required',
            'phone' => 'required',
            'company' => '',
            'occupation' => '',
            'experience' => '',
            'device_type' => 'required',
            'fcm_token' => '',
            'app_version' => '',
            'device_id' => '',
        ]);

        if ($validate->fails()) {
            return $this->formatResponse(
                'error',
                'validation-error',
                $validate->errors()->first()
            );
        }

        if (User::where('email', $request->email)->count()) {
            // if user found
            return $this->formatResponse(
                'error',
                'user-already-exists'
            );
        }

        // delete old verification if exists
        UserVerification::where('email', $request->email)->delete();

        // $token = rand(1000, 9999);
        $token = 1111;
        Mail::to($request->email)->send(new VerificationEmail($token));
        $verification = new UserVerification();
        $verification->email = $request->email;
        $verification->token = $token;
        $verification->save();

        return $this->formatResponse(
            'success',
            'code-has-been-sent',
            [
                'token' => $token,
            ]
        );
    }

    public function resendCode(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validate->fails()) {
            return $this->formatResponse(
                'error',
                'validation-error',
                $validate->errors()->first()
            );
        }

        // get old verification if exists
        $verification = UserVerification::where('email', $request->email)->first();

        if ($verification) {

            $token = rand(1000, 9999);

            Mail::to($request->email)->send(new VerificationEmail($token));

            $verification->token = $token;
            $verification->status = UserVerification::STATUS_PENDING;
            $verification->save();

            return $this->formatResponse(
                'success',
                'code-has-been-sent',
                [
                    'token' => $token,
                ]
            );
        } else {
            return $this->formatResponse(
                'error',
                'email-not-exist'
            );
        }
    }

    public function verifyEmail(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'first_name' => 'required|string',
            'middle_name' => 'string',
            'last_name' => 'required|string',
            'ar_name' => 'required|string',
            'user_name' => 'string',
            'password' => 'required|min:6',
            'country_id' => 'required',
            'cpr' => 'required|min:5|max:10',
            'dob' => 'required|date',
            'gender' => 'required',
            'phone' => 'required',
            'company' => '',
            'occupation' => '',
            'experience' => '',
            'device_type' => 'required',
            'fcm_token' => '',
            'app_version' => '',
            'device_id' => '',
        ]);

        if ($validate->fails()) {
            return $this->formatResponse(
                'error',
                'validation-error',
                $validate->errors()->first()
            );
        }

        $userVerification = UserVerification::where('token', $request->token)
            ->where('email', $request->email)
            ->first();
        //check if exist
        if ($userVerification) {

            if ($userVerification->status !== UserVerification::STATUS_PENDING) {
                return $this->formatResponse(
                    'error',
                    'verification-code-is-expired'
                );
            }

            $userVerification->status = UserVerification::STATUS_VERIFIED;
            $userVerification->save();

            // return $this->formatResponse(
            //     'success',
            //     'email-has-been-verified'
            // );
        } else {
            return $this->formatResponse(
                'error',
                'verification-code-not-valid-or-email-not-exist'
            );
        }

        $verification = UserVerification::where('email', $request->email)
            ->where('token', $request->token)
            ->where('status', UserVerification::STATUS_VERIFIED)
            ->first();
        if (!$verification) {
            return $this->formatResponse(
                'error',
                'unauthorized-user'
            );
        }

        if (User::where('email', $request->email)->count()) {
            // if user found
            return $this->formatResponse(
                'error',
                'user-already-exists'
            );
        }
        $user = new User();
        $user->first_name = $request->first_name;
        $user->middle_name = $request->middle_name;
        $user->last_name = $request->last_name;
        $user->user_name = $request->user_name;
        $user->password = Hash::make($request->password);
        $user->gender = $request->gender;
        $user->country_id = $request->country_id;
        $user->cpr = $request->cpr;
        $user->phone = $request->phone;
        $user->ar_name = $request->ar_name;
        $user->occupation = $request->occupation;
        $user->experience = $request->experience;
        if(isset($user->company))
            $user->company = $request->company;
        $user->headline = $request->headline;
        $user->email = $request->email;
        $user->dob = $request->dob;
        $user->is_verified = true;
        $user->last_activity_at = now();
        $user->role_id = Role::STUDENT;
        $user->device_type = $request->device_type;

        if($user->role_id == Role::STUDENT)
        {
            $user->area_id = $request->area_id;
            $user->school_id = $request->school_id;
            $user->area_name = $request->area_name;
            $user->school_name = $request->school_name;
        }

        if (isset($request->fcm_token)) {
            $user->fcm_token = $request->fcm_token;
        }
        if ($request->app_version) {
            $user->app_version = $request->app_version;
        }
        if ($request->device_id) {
            $user->device_id = $request->device_id;
        }
        $user->save();

        $token = $user->createToken($request->device_id ?: $request->device_type)->plainTextToken;
        $user->api_token = $token;
        Mail::to($request->email)->send(new AccountCreated($user->first_name));

        return $this->formatResponse('success', 'signup-Successfully',$user);
    }

    public function socialLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => '',
            'provider_id' => 'required',
            'provider' => 'required|in:apple,google', // apple/google
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'device_type'=> 'required',
            'device_id'=> 'nullable|string',
            'app_version' => 'nullable|string',
            'fcm_token' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->formatResponse(
                'error',
                'validation-error',
                $validator->errors()->first()
            );
        }

        if (strtolower($request->provider) == "apple") {
            $user = User::where("provider_id", trim($request->provider_id))->first();
        } elseif (strtolower($request->provider) == "google") {
            $user = User::where("google_id", trim($request->provider_id))->first();
        }

        if (!$user && isset($request->email) && trim($request->email)) {
            $user = User::where("email", trim($request->email))->first();
        }

        if (!$user) {
            if(!$request->email){
                return $this->formatResponse(
                    'error',
                    'email-not-found'
                );
            }
            $user = new User();
            $user->email = $request->email ?? null;

            $user->password = Hash::make('INJAZ' . $request->provider . $request->provider);
            $user->role_id = Role::STUDENT;
            if ($user->email) {
                $user->is_verified = 1;
            }

            $email = $user->email;
            if ($email && $user->is_verified == 1) {
                Mail::to($request->email)->send(new AccountCreated($user->first_name));
            }
        }
        $user->provider = $request->provider;
        $user->provider_id = $request->provider_id;

        if ($request->first_name) {
            $user->first_name = $request->first_name;
        }
        if ($request->last_name) {
            $user->last_name = $request->last_name;
        }
        if ($request->fcm_token) {
            $user->fcm_token = $request->fcm_token;
        }
        if ($request->app_version) {
            $user->app_version = $request->app_version;
        }
        if ($request->device_id) {
            $user->device_id = $request->device_id;
        }
        if ($request->device_type) {
            $user->device_type = $request->device_type;
        }

        $user->is_verified = 1;
        $user->save();

        $user->api_token = $user->createToken($request->device_id ?: $request->device_type)->plainTextToken;

        return $this->formatResponse(
            'success',
            'login-successfully',
            $user
        );
    }

}
