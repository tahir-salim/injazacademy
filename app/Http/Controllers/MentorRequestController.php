<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MentorRequest;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MentorRequestController extends Controller
{
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'file' => 'required|mimes:jpg,pdf,png,doc,docx',
            'brief' => 'required|string',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
        ]);
        if ($validate->fails()) {
            return $this->formatResponse(
                'error',
                'validation-error',
                $validate->errors()->first()
            );
        }

        $prevRequest = MentorRequest::where('email', Auth::user()->email)->first();
        if ($prevRequest && $prevRequest->status == MentorRequest::PENDING) {
            return $this->formatResponse('error', 'already-applied-request-pending');
        }

        $path = Storage::disk('s3')->put('files/resume', new File($request->file));
        $mentorRequest = new MentorRequest();
        $mentorRequest->first_name = Auth::user()->first_name;
        $mentorRequest->last_name = Auth::user()->last_name;
        $mentorRequest->email = Auth::user()->email;
        $mentorRequest->status = 'PENDING';
        $mentorRequest->brief = $request->brief;
        $mentorRequest->file = $path;
        $mentorRequest->save();
        return $this->formatResponse('success', 'mentor-request-inserted', $mentorRequest, 200);
    }

    public function checkRequestStatus(Request $request)
    {
        return $this->formatResponse(
            'success',
            'request-status',
            [
                'requestStatus' => MentorRequest::where('email', Auth::user()->email)->first(),
                'user' => Auth::user(),
            ]
        );
    }
}
