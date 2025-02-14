<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\MarkedWatch;
use App\Models\Content;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\WatchContent;
use App\Services\ProgramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WatchContentController extends Controller
{

    protected $programService;
    public function __construct(ProgramService $programService)
    {
        $this->programService = $programService;
    }

    public function markedVideoWatched(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'content_id' => 'required',
            'time_spent'=> 'required',
            'is_file' => 'required',
            'program_id'=> 'required'
        ]);
        if ($validate->fails()) {
            return $this->formatResponse('error', 'validation-error', $validate->errors()->first());
        }

        $enrollment = Enrollment::where('user_id' , Auth::id())
                    ->where('program_id' , $request->program_id)
                    ->first();

        if(!$enrollment){
            return $this->formatResponse('error', 'no-enrollment');
        }

        $watchedVideo = WatchContent::where('user_id', Auth::id())
            ->where('content_id', $request->content_id)
            ->first();
        if ($watchedVideo) {
            if ($watchedVideo->time_spent < 100) {
                $watchedVideo->time_spent = $request->time_spent;
                $watchedVideo->save();
                // if($watchedVideo->time_spent >= 75 && $watchedVideo->time_spent < 90)
                //     return $this->formatResponse('success', 'watch-video-progress-updated-mark' , [ 'watch-history' => $watchedVideo ]);

                if(!$enrollment->is_all_content_watched && $watchedVideo->time_spent >= 90){
                    $contentCount = WatchContent::where([
                        ['user_id' , '=' , $watchedVideo->user_id],
                        ['program_id' , '=' , $watchedVideo->program_id]
                    ])->count();

                    if($contentCount >= Content::where('program_id' , $watchedVideo->program_id)->count())
                    {
                        $enrollment->is_all_content_watched = true;
                        $enrollment->save();

                        $program = $this->programService->getProgramDetail($watchedVideo->program_id);

                        return $this->formatResponse('success', 'course-completed' , [
                            'watch-history' => $watchedVideo,
                            'program' => $this->programService->addExtraAttributes($program)
                        ]);
                    }

                    return $this->formatResponse('success', 'watch-video-progress-updated-mark' , [ 'watch-history' => $watchedVideo ]);
                }

                return $this->formatResponse('success', 'watch-video-progress-updated' , [ 'watch-history' => $watchedVideo ]);
            } else {
                return $this->formatResponse('success', 'video-watched');
            }

        } else {

            $watchedVideo = new WatchContent();
            $watchedVideo->content_id = $request->content_id;
            $watchedVideo->user_id = Auth::id();
            $watchedVideo->program_id = $request->program_id;
            if($request->is_file == 'Yes')
                $watchedVideo->time_spent = 100;
            else
                $watchedVideo->time_spent = 0;
            $watchedVideo->save();

            if(!$enrollment->is_all_content_watched && $watchedVideo->time_spent >= 90 && $request->is_file == 'Yes'){
                $contentCount = WatchContent::where([
                    ['user_id' , '=' , $watchedVideo->user_id],
                    ['program_id' , '=' , $watchedVideo->program_id]
                ])->count();

                if($contentCount >= Content::where('program_id' , $watchedVideo->program_id)->count())
                {
                    $enrollment->is_all_content_watched = true;
                    $enrollment->save();

                    $program = $this->programService->getProgramDetail($watchedVideo->program_id);

                    return $this->formatResponse('success', 'course-completed' , [
                        'watch-history' => $watchedVideo,
                        'program' => $this->programService->addExtraAttributes($program)
                    ]);
                }

                return $this->formatResponse('success', 'watch-video-progress-updated-mark' , [ 'watch-history' => $watchedVideo ]);
            }

            // if(!$enrollment->is_all_content_watched){
            //     $contentCount = WatchContent::where([
            //         ['user_id' , '=' , $watchedVideo->user_id],
            //         ['program_id' , '=' , $watchedVideo->program_id]
            //     ])->count();

            //     if($contentCount >= Content::where('program_id' , $watchedVideo->program_id)->count())
            //     {
            //         $enrollment->is_all_content_watched = true;
            //         $enrollment->save();

            //         $program = $this->programService->getProgramDetail($watchedVideo->program_id);

            //         return $this->formatResponse('success', 'course-completed' , [
            //            'watch-history' => $watchedVideo,
            //            'program' => $this->programService->addExtraAttributes($program)
            //         ]);
            //     }
            // }
        }


        return $this->formatResponse('success', $request->is_file == 'Yes' ? 'watch-video-progress-updated-mark' : 'watch-video-add' ,  [ 'watch-history' => $watchedVideo ]);

    }

    public function watchHistory(Request $request)
    {

        $limit = $request->query('limit') ?: 30;
        $view_all = $request->query('view_all') ?? 0;

        $watchHistory = WatchContent::where('user_id' , '=' , Auth::id())
            ->with(['content:id,title,order_number,chapter_id' , 'content.chapter' , 'program:id,title'])
            ->where('time_spent' ,'<', 100)
            // ->selectRaw("id,created_at,updated_at,program_id,SUBSTRING_INDEX( GROUP_CONCAT(content_id ORDER BY updated_at DESC), ',', 1) content_id,SUBSTRING_INDEX( GROUP_CONCAT(time_spent ORDER BY updated_at DESC), ',', 1) time_spent")
            // ->groupBy('program_id')
            ->orderBy('updated_at','desc')
            ->paginate($limit);

        // $watchHistory = $watchHistory->toArray();
        // $pagination = [];
        // foreach($watchHistory['data'] as $watchedContent)
        // {
        //     if($view_all == 0 && $watchedContent['time_spent'] < 100)
        //         $pagination[] = $watchedContent;
        //     else if($view_all == 1)
        //         $pagination[] = $watchedContent;
        // }

        // $watchHistory['data'] = $pagination;

        return $this->formatResponse('success', 'watch-video-history',
          $watchHistory
        );
    }

    public function enrollments(Request $request, $programID)
    {

        // $limit = $request->query('limit') ?: 30;
        $userIds = User::whereHas('userPrograms', function ($q) use ($programID) {
            $q->where('program_id', $programID);
        })
            ->pluck('id');

        $enrollments = WatchContent::with(['user', 'content.chapter' => function ($q) {
            $q->select('id', 'order_number as chapter_number');
        }])
            ->whereIn('user_id', $userIds)
            ->get();

        return $this->formatResponse('success', 'program-enrollments', $enrollments);
    }

}
