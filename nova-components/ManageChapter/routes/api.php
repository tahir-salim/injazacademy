<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

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

Route::get('/course/{id}', function (Request $request) {

    $course = \App\Models\Program::where('id', $request->id)->first();

    $chapters = \App\Models\Chapter::where('program_id', $request->id)->get();
   

    $chaptersSorted = $chapters->sortBy('order_number');

    return ['status'=> true, 'data' => ['chapters' => $chaptersSorted->values()->all(), 'course' => $course]];
});

Route::post('/delete-chapter', function (Request $request) {

    \App\Models\Chapter::find($request->chapterId)->delete();

    $chapters = \App\Models\Chapter::where('program_id' , $request->program_id)->get();
    $order_number = 1;
    foreach ($chapters as $chapter){
        DB::table('chapters')
            ->where('id', $chapter['id'])
            ->update(['order_number' => $order_number]);
        $order_number++;
    }
    
    return ["detail" => true];
});

Route::post('/update-chapter-series', function (Request $request) {

    $chapters = $request->chapters;
    $order_number = 1;
    foreach ($chapters as $chapter){
        DB::table('chapters')
            ->where('id', $chapter['id'])
            ->update(['order_number' => $order_number]);
        $order_number++;
    }
    return ["detail" => true];
});

Route::post('/add-chapter', function (Request $request) {

    DB::table('chapters')->insert([
        'title' => $request->title,
        'program_id' => $request->courseId,
        'order_number' => $request->order_number,
        'status' => $request->status,
        "created_at" => \Carbon\Carbon::now(),
        "updated_at" => \Carbon\Carbon::now(),
        ]);
    return ["detail" => true];
});

Route::post('/update-chapter', function (Request $request) {

    \App\Models\Chapter::where('id', $request->chapterid)
        ->update([
            'title' => $request->title,
            'status' => $request->status
        ]);

    return ["detail" => true];
});