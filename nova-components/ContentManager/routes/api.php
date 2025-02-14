<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

// Route::get('/endpoint', function (Request $request) {
//     //
// });

Route::get('/program/{id}', function ($id) {

    $course = \App\Models\Program::find($id);
 

    foreach ($course->chapter as $chapter) {
        $chapter->contents->toArray();
    }
  
    return ["detail" => $course];
});

// Delete Content API
Route::get('/delete-content/{id}/type/{type}', function ($id, $type) {
    $id = $id;
    $type = $type;

    $content = \App\Models\Content::find($id);

    if($content->data){
        Storage::disk('s3')->delete($content->data);
    }

    $content->delete();

    return ["result" => true];
});

//Update List
Route::post('/update-drag-drop-detail', function (Request $request) {

    $chapters = $request->chapters; // get all the chapters

    foreach ($chapters as $chapter) {
        $order_number = 0;
        foreach ($chapter['contents'] as $item) {
            // if(isset($item['vimeo_id'])) { 
            //     DB::table('videos')
            //         ->where('id', $item['id'])
            //         ->update(['order_number' => $order_number,
            //                  'chapter_id' => $chapter['id']]);
            // } else if (isset($item['file'])) {
            //     DB::table('attachments')
            //         ->where('id', $item['id'])
            //         ->update(['order_number' => $order_number,
            //                   'chapter_id' => $chapter['id']]);
            // } else if (isset($item['link'])) {
            //     DB::table('hyperlinks')
            //         ->where('id', $item['id'])
            //         ->update(['order_number' => $order_number,
            //                   'chapter_id' => $chapter['id']]);
            // }
            DB::table('contents')->where('id' , $item['id'])->update(['order_number' => $order_number , 'chapter_id' => $chapter['id']]);
            $order_number++;
        }
    }

    return ["result" => true];
});

Route::post('/add-attachment', function (Request $request) {

        
        // save File To S3
        $filePath = $request->file('file')
            ->store( 'files/attachments','s3');
    
        // fetch file name with extension
        $originalName = $request->file('file')->getClientOriginalName();
    
        $data = [
            "download_link" => $filePath,
            "original_name" => $originalName,
        ];

        $content = new \App\Models\Content();
        $content->program_id =  $request->course_id;
        $content->chapter_id =  $request->chapterId;
        $content->title =  $request->title;
        $content->data = $data["download_link"];
        $content->type =  $request->type;
        $content->file_name = $originalName;
        // $attachment->is_free =  $request->is_free;
        // $attachment->scheduled_date =  $request->scheduledDate;
        $content->order_number =  $request->order_number;
        $content->duration =  $request->duration;
        $content->hours = $request->hours;
        $content->mins = $request->mins;
        $content->status = 'PUBLISHED';
        $content->created_at = \Carbon\Carbon::now();
        $content->updated_at =   \Carbon\Carbon::now();
        $content->save();
    
        return ["result" => true];
});

Route::post('/update-attachment', function (Request $request) {

    if($request->file('file')){
        // $extension = $request->file('file')->getClientOriginalExtension();
        $originalName = $request->file('file')->getClientOriginalName();

        // $filename = uniqid() . '_' . time() . '.' . $extension;

        $filePath = $request->file('file')
            ->store( 'files/attachments','s3');

        $data = [
            "download_link" => $filePath,
            "original_name" => $originalName,
        ];
    }

    $temp = [
        "title" => $request->title,
        "chapter_id" => $request->chapterId,
        // "is_free" => $request->is_free,
        "type" => $request->type,
        "duration" =>  $request->duration,
        "hours" =>  $request->hours,
        "mins" => $request->mins
        // "scheduled_date" => $request->scheduledDate,
    ];


    if($request->file('file'))
    {
        $temp['data'] = $data["download_link"];
        $temp['file_name'] = $originalName;

    }



    \App\Models\Content::where('id', $request->contentId)->update($temp);

    return ['status' =>true];
});

// Add Video Content
Route::post('/add-video', function (Request $request) {

    $data = [
        "program_id" => $request->courseId,
        "title" => $request->title,
        "type" => $request->type,
        "url" => $request->vimeoId,
        // "is_free" => $request->is_free,
        "duration" => $request->duration,
        "hours" => $request->hours,
        "mins" => $request->mins,
        "chapter_id" => $request->chapterId,
        "order_number" => $request->order_number,
        "status" => "PUBLISHED",
       'created_at' => \Carbon\Carbon::now(),
        'updated_at' =>   \Carbon\Carbon::now()

    ];

    \App\Models\Content::insert($data);

    return ["result" => $data];
});

//  // Update video Content API
 Route::post('/update-video', function (Request $request) {

    \App\Models\Content::where('id', $request->contentId)->update([
         // "course_id" => $request->courseId,
         "title" => $request->title,
         "type" => $request->type,
         "url" => $request->vimeoId,
         // "is_free" => $request->is_free,
         "duration" => $request->duration,
         "hours" => $request->hours,
         "mins" => $request->mins,
         "chapter_id" => $request->chapterId,
         "order_number" => $request->order_number,
         "status" => "PUBLISHED",
    ]);

    return ['status' => true];
});


Route::get('/delete/{id}', function ($id) {

    $content = \App\Models\Content::find($id);

    if($content->data){
        Storage::disk('s3')->delete($content->data);
    }

    $content->data = null;
    $content->file_name = null;
    $content->save();
        
   

    return ["result" => $content];
});
