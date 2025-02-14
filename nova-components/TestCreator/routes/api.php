<?php

use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/create', function(Request $request){

    $request->validate([
        'title' => 'required',
        'programId' => 'required',
        'questions' => 'required|array',
        'questions.*.question' => 'required|string',
        // 'questions.*.correct_answer' => 'required|int',
        // 'questions.*.answers' => 'required|array',
        'questions.*.answers.*.answer' => 'required|string',
        'totalMarks' => 'required',
        'passingMarks' => 'lte:totalMarks'
    ]);


    $test = \App\Models\Test::create([
        'title' => $request->title,
        'sub_title' => $request->sub_title,
        'body' => $request->body,
        'program_id' => $request->programId,
        'total_marks' => $request->totalMarks,
        'passing_criteria' => $request->passingMarks,
    ]);


    $program = \App\Models\Program::find($request->programId);
    $program->quiz_required = true;
    $program->save();


    if(isset($request->questions))
        foreach($request->questions as $question)
        {

            $Question = new \App\Models\TestQuestion();
            $Question->test_id = $test->id;
            $Question->question = $question['question'];
            $Question->correct_answer = $question['correct_answer'];
            $Question->order_number = $question['order_number'];
            $Question->marks = $question['marks'];
            $Question->question_type = $question['type'];
            $Question->save();
            if(isset($question['answers']) && count($question['answers']) > 0 )

                foreach($question['answers'] as $answer)
                {
                //dd($answer['order_number']);
                    $Answer = new \App\Models\QuestionAnswer();
                    $Answer->question_id = $Question->id;
                    $Answer->answer = $answer['answer'];
                    $Answer->order_number = $answer['order_number'];
                    $Answer->save();
                }
            else if( $question['type'] == 'TEXT' )
            {
                    $Answer = new \App\Models\QuestionAnswer();
                    $Answer->question_id = $Question->id;
                    $Answer->answer = $question['detailedAnswer'];
                    $Answer->order_number = 0;
                    $Answer->save();
            }
        }

    return response()->json([
        'status'  => 'success',
        'message' => 'Test Created',
        'data'    => $test->id,
    ], 200);
});

Route::post('/init' , function(Request $request){

    $data = [];
    $testID = $request->test_id;
    // dd($request->is_duplicate);

    if($testID)
    {
        $data['test'] = \App\Models\Test::with(['testQuestions' => function($q){
            $q->with('questionAnswers');
        }, 'program'])->find($testID);

        if(!$data['test'])
            abort(403);

        if($request->is_duplicate)
        {
            $data['test']->unsetRelation('program');
            $data['test']['program_id'] = null;
            // dd('jahs');
        }
    }

    // dd($data['test']);

    return response()->json($data);
});

Route::get('/programs' , function(Request $request){

    $data = \App\Models\Program::doesntHave('test')->courses()->get();

    return response()->json($data);
});

Route::post('/update/{test}' , function(Request $request , \App\Models\Test $test){
    $request->validate([
        // 'title' => 'required',
        // 'sub_title' => 'required',
        // 'body' => 'required',
        // 'questions' => 'required|array',
        // 'questions.*.question' => 'required',
        // 'questions.*.question.answers.*.answer' => 'required'

        'title' => 'required',
        'programId' => 'required',
        'questions' => 'required|array',
        'questions.*.question' => 'required|string',
        // 'questions.*.correct_answer' => 'required|int',
        // 'questions.*.answers' => 'required|array',
        'totalMarks' => 'required',
        'passingMarks' => 'lte:totalMarks',
        'questions.*.answers.*.answer' => 'required|string',
    ]);

    //refresh programs
    \App\Models\Program::doesntHave('test')
            ->update([ 'quiz_required' => false ]);

    $program = \App\Models\Program::find($request->programId);
    $program->quiz_required = true;
    $program->save();


    $test->title = $request->title;
    $test->total_marks = $request->totalMarks;
    $test->passing_criteria = $request->passingMarks;
    // $test->sub_title = $request->test['sub_title'];
    // $test->body = $request->test['body'];
    $test->program_id = $request->programId;
    // $test->testQuestions->questionAnswers()->delete();
    foreach($test->testQuestions() as $question){
        $question->questionAnswers()->delete();
    }
    $test->testQuestions()->delete();
    $test->save();

    //$test = \App\Models\Test::find($request->test);
    //dd($request->questions);
    if(isset($request->questions))
    foreach($request->questions as $question)
    {

        $Question = new \App\Models\TestQuestion();
        $Question->test_id = $test->id;
        $Question->question = $question['question'];
        $Question->correct_answer = $question['correct_answer'];
        $Question->order_number = $question['order_number'];
        $Question->marks = $question['marks'];
        $Question->question_type = $question['type'];
        $Question->save();
        if(isset($question['answers']) && count($question['answers']) > 0 )

            foreach($question['answers'] as $answer)
            {
            //dd($answer['order_number']);
                $Answer = new \App\Models\QuestionAnswer();
                $Answer->question_id = $Question->id;
                $Answer->answer = $answer['answer'];
                $Answer->order_number = $answer['order_number'];
                $Answer->save();
            }
        else if($question['type'] == 'TEXT' )
        {
                $Answer = new \App\Models\QuestionAnswer();
                $Answer->question_id = $Question->id;
                $Answer->answer = $question['detailedAnswer'];
                $Answer->order_number = 0;
                $Answer->save();
        }
    }
        // foreach($request->questions as $question)
        // {

        //     $Question = new \App\Models\TestQuestion();
        //     $Question->test_id = $test->id;
        //     $Question->question = $question['question'];
        //     $Question->correct_answer = $question['correct_answer'];
        //     $Question->order_number = $question['order_number'];
        //     $Question->save();
        //     if(isset($question['answers']))
        //         foreach($question['answers'] as $answer)
        //         {
        //         //dd($answer['order_number']);
        //             $Answer = new \App\Models\QuestionAnswer();
        //             $Answer->question_id = $Question->id;
        //             $Answer->answer = $answer['answer'];
        //             $Answer->order_number = $answer['order_number'];
        //             $Answer->save();
        //         }
        // }

    return response()->json([
        'status'  => 'success',
        'message' => 'Test Created',
        'data'    => $test->id,
    ], 200);
});

// Route::get('/endpoint', function (Request $request) {
//     //
// });
