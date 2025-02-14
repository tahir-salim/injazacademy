<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\QuestionAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionAnswerController extends Controller
{
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'question_id' => 'required|int',
            'answer' => 'required|string',
            'order_number' => 'required|boolean',
        ]);
        if ($validate->fails()) {
            return $this->formatResponse(
                'error',
                'validation-error',
                $validate->errors()->first()
            );
        }
        $questionAnswer = QuestionAnswer::create($request->all());
        return $this->formatResponse('success', 'question_answer-insert', $questionAnswer, 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $questionAnswer = QuestionAnswer::where('question_id', $id)->get();
        return $this->formatResponse('success', 'question_answer-find', $questionAnswer, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

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
            'question_id' => 'required|int',
            'answer' => 'required|string',
            'order_number' => 'required|boolean',
        ]);
        if ($validate->fails()) {
            return $this->formatResponse(
                'error',
                'validation-error',
                $validate->errors()->first()
            );
        }

        $questionAnswer = QuestionAnswer::find($id)->update($request->all());
        return $this->formatResponse('success', 'question_answer-update', $questionAnswer, 200);
    }

}
