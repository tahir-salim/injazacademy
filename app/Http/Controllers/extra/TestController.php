<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TestController extends Controller
{

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required|string',
            'sub_title' => 'required|string',
            'body' => 'required|string',
            'program_id' => 'required|int',
        ]);
        if ($validate->fails()) {
            return $this->formatResponse('error', 'validation-error', $validate->errors()->first());
        }

        $test = Test::create($request->all());
        return $this->formatResponse('success', 'test-inserted', $test);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($program_id)
    {
        $test = Test::where('program_id', $program_id)->get();
        return $this->formatResponse('success', 'test-show', $test);

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
            'title' => 'required|string',
            'sub_title' => 'required|string',
            'body' => 'required|string',
        ]);
        if ($validate->fails()) {
            return $this->formatResponse('error', 'validation-error', $validate->errors()->first());
        }

        $test = Test::find($id);
        $test->title = $request->title;
        $test->sub_title = $request->sub_title;
        $test->body = $request->body;
        $test->save();
        return $this->formatResponse('success', 'test-updated', $test);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

}
