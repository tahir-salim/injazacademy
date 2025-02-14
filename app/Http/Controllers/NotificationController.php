<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public function notifications(Request $request)
    {
        $is_mentor = $request->query('is_mentor') ?? false;
        return $this->formatResponse('success' , 'notification-messages',
            Notification::with(['user:id,avatar','related:id,avatar'])
            ->when($is_mentor, function($q){
                $q->where('user_id', Auth::id())
                  ->orWhere('user_list', 'like', '%"'.Auth::id().'"%');
            })
            ->when(!$is_mentor, function($q){
                $q ->where('user_id', Auth::id());
            })
            ->orderBy('updated_at', 'desc')->paginate(10)
        );
    }

    public function readNotification($notification_id)
    {
        Notification::find($notification_id)->delete();
        return $this->formatResponse('success', 'notification-read');
    }

    public function toggleNotification(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'toggle' => 'required|boolean',
        ]);
        if ($validate->fails()) {
            return $this->formatResponse(
                'error',
                'validation-error',
                $validate->errors()->first()
            );
        }

        Auth::user()->is_notification = $request->toggle;
        Auth::user()->save();

        return $this->formatResponse('success', 'notifications-toggled', [
            'toggle' => Auth::user()->is_notification
        ]);
    }
}
