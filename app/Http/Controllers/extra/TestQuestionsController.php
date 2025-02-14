<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\TestQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TestQuestionsController extends Controller
{

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'test_id' => 'required|int',
            'question' => 'required|string',
            'correct_answer' => 'required|string',
            'order_number' => 'required|int',
        ]);
        if ($validate->fails()) {
            return $this->formatResponse('error', 'validation-error', $validate->errors()->first());
        }

        $testQuestion = TestQuestion::create($request->all());
        return $this->formatResponse('success', 'test_question-inserted', $testQuestion);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($test_id)
    {
        $test = TestQuestion::where('test_id', $test_id)->get();
        return $this->formatResponse('success', 'test_questions-show', $test);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'test_id' => 'required|int',
            'question' => 'required|string',
            'correct_answer' => 'required|string',
            'order_number' => 'required|int',
        ]);
        if ($validate->fails()) {
            return $this->formatResponse('error', 'validation-error', $validate->errors()->first());
        }

        $testQuestion = TestQuestion::find($id)->update($request->all());
        return $this->formatResponse('success', 'test_question-updated', $testQuestion);

    }

}
