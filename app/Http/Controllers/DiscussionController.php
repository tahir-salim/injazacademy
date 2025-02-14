<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Discussion;
use App\Models\Enrollment;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DiscussionController extends Controller
{

    protected $enrollment;
    public function __construct(Enrollment $enrollment)
    {
        $this->enrollment = $enrollment;
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'program_id' => 'required|integer',
            'body' => 'required',
            'is_mentor' => 'nullable',
        ]);

        if ($validate->fails()) {
            return $this->formatResponse('error', 'validation-error', $validate->errors()->first());
        }

        $discussion = new Discussion();
        $discussion->body = $request->body;
        $discussion->user_id = Auth::id();
        $discussion->program_id = $request->program_id;
        $discussion->is_pinned = $request->is_pinned;

        if ($request->is_mentor) {
            $discussion->is_mentor = true;
        } else {
            // $enrollment = Enrollment::where([
            //     ['user_id', '=', Auth::id()],
            //     ['program_id', '=', $request->program_id],
            // ])->first();
            $enrollment = $this->enrollment->auth()->whereByProgram($request->program_id)->first();
            if ($enrollment) {
                $discussion->enrollment_id = $enrollment->id;
            } else {
                $this->formatResponse('error', 'Enrollment does not exists');
            }

        }

        $discussion->save();

        return $this->formatResponse(
            'success',
            'post-created',
            $discussion
                ->loadMissing('user')
                ->loadCount('replies', 'likes', 'likedByMe', 'myDiscussion')
        );
    }

    public function storeReply(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'program_id' => 'required|integer',
            'reply_id' => 'required|integer',
            'body' => 'required',
            'is_mentor' => 'nullable',
        ]);
        if ($validate->fails()) {
            return $this->formatResponse('error', 'validation-error', $validate->errors()->first());
        }

        $discussion = new Discussion();
        $discussion->body = $request->body;
        $discussion->reply_id = $request->reply_id;
        $discussion->user_id = Auth::id();
        $discussion->program_id = $request->program_id;

        if ($request->is_mentor) {
            $discussion->is_mentor = true;
        } else {
            // $enrollment = Enrollment::where([
            //     ['user_id', '=', Auth::id()],
            //     ['program_id', '=', $request->program_id],
            // ])->first();
            $enrollment = $this->enrollment->auth()->whereByProgram($request->program_id)->first();

            if (!$enrollment) {
                return $this->formatResponse('error', 'enrollment-not-found');
            }

            $discussion->enrollment_id = $enrollment->id;
        }

        $discussion->save();

        return $this->formatResponse(
            'success',
            'discussion-inserted',
            $discussion->loadMissing('user')
                ->loadCount('myDiscussion')
        );
    }

    public function discussionProgram(Request $request, $program_id)
    {

        $limit = $request->query('limit') ?? 10;

        $discussions = Discussion::where('program_id', $program_id)
            ->appendExtraAttributes()
            ->parents()
            ->latestOrder()
            ->paginate($limit);

        return $this->formatResponse('success', 'discussion', $discussions);
    }

    public function discussionReplies(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'discussion_id' => 'required|integer',
        ]);

        if ($validate->fails()) {
            return $this->formatResponse('error', 'validation-error', $validate->errors()->first());
        }

        $discussion = Discussion::userInfo()->withProgramTitle()->find($request->discussion_id);
        if(!$discussion){
            return $this->formatResponse('error', 'no-discussion-found');
        }
        $limit = $request->query('limit') ?? 10;
        $discussions = Discussion::userInfo()
            ->where([
                ['reply_id', '=', $request->discussion_id],
            ])
            // ->latest()
            ->latest()
            ->paginate($limit)
            ->toArray();

        $discussions['discussion'] = $discussion;

        $discussions['data'] = array_reverse($discussions['data']);

        unset($discussions['links']);

        return $this->formatResponse('success', 'replies-to-discussions', $discussions);
    }

    public function likePost(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'discussion_id' => 'required|integer',
        ]);

        if ($validate->fails()) {
            return $this->formatResponse('error', 'validation-error', $validate->errors()->first());
        }

        $likedDiscussion = Like::where([
            ['likeable_id', '=', $request->discussion_id],
            ['likeable_type', '=', Discussion::class],
        ])->authUserLikes()->first();

        if ($likedDiscussion) {
            if ($likedDiscussion->is_like == true) {
                $likedDiscussion->is_like = false;
            } else {
                $likedDiscussion->is_like = true;
            }

            $likedDiscussion->save();
            return $this->formatResponse('success', 'discussion-like-updated', $likedDiscussion);
        }

        $likedDiscussion = new Like();
        $likedDiscussion->likeable_id = $request->discussion_id;
        $likedDiscussion->likeable_type = Discussion::class;
        $likedDiscussion->is_like = true;
        $likedDiscussion->user_id = Auth::id();
        $likedDiscussion->save();

        return $this->formatResponse('success', 'discussion-like-created', $likedDiscussion);
    }

    public function unPinPost(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'discussion_id' => 'required|integer',
        ]);

        if ($validate->fails()) {
            return $this->formatResponse('error', 'validation-error', $validate->errors()->first());
        }

        $discussion = Discussion::find($request->discussion_id);

        if (!$discussion) {
            return $this->formatResponse('error', 'post-not-found');
        }

        $discussion->is_pinned = false;
        $discussion->save();

        return $this->formatResponse('success', 'post-unpinned');
    }

    public function deletePost(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'discussion_id' => 'required|integer',
        ]);

        if ($validate->fails()) {
            return $this->formatResponse('error', 'validation-error', $validate->errors()->first());
        }

        $discussion = Discussion::find($request->discussion_id);

        if (!$discussion) {
            return $this->formatResponse('error', 'post-not-found');
        }

        $discussion->replies()->delete();
        $discussion->delete();
        return $this->formatResponse('success', 'post-deleted');
    }

    public function actionOnDiscussion(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'discussion_id' => 'required|integer',
        ]);

        if ($validate->fails()) {
            return $this->formatResponse('error', 'validation-error', $validate->errors()->first());
        }

        $discussion = Discussion::find($request->discussion_id);

        if (!$discussion) {
            return $this->formatResponse('error', 'post-not-found');
        }

        $discussion->is_disabled = !$discussion->is_disabled;
        $discussion->save();

        return $this->formatResponse('success', $discussion->is_disabled == true ? 'post-disabled' : 'post-enabled', $discussion);
    }
}
