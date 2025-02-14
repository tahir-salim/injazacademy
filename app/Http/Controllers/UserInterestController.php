<?php

namespace App\Http\Controllers;

use App\Models\UserInterest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserInterestController extends Controller
{
    public function userInterest(Request $request)
    {
        $userInterest = UserInterest::with('tag')
            ->where('user_id', Auth::id())
            ->get();

        return $this->formatResponse('success', 'get-user-interest', $userInterest);
    }

    public function updateUserInterest(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'tag_ids' => 'required|array',
        ]);
        if ($validate->fails()) {
            return $this->formatResponse(
                'error',
                'validation-error',
                $validate->errors()->first()
            );
        }

        Auth::user()->userTag()->sync($request->tag_ids);

        return $this->formatResponse('success', 'user-interest-updated');
    }
}
