<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $content = Content::all();
        return $this->formatResponse('success', 'content-get', $content, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required|string',
            'type' => 'required|string',
            'url' => 'required|string',
            'data' => 'required|string',
            'status' => 'required|string',
            'duration' => 'required',
            'language' => 'required',
            'chapter_id' => 'required|int',

        ]);
        if ($validate->fails()) {
            return $this->formatResponse('error', 'validation-error', $validate->errors()->first());
        }

        $content = Content::create($request->all());
        return $this->formatResponse('success', 'content-create', $content, 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($content_id)
    {
        $content = Content::find($content_id);
        return $this->formatResponse('success', 'content-get', $content, 200);
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
    public function update(Request $request, $content_id)
    {$validate = Validator::make($request->all(), [
        'title' => 'required|string',
        'type' => 'required|string',
        'url' => 'required|string',
        'data' => 'required|string',
        'status' => 'required|string',
        'duration' => 'required',
        'language' => 'required',
        'chapter_id' => 'required|int',

    ]);
        if ($validate->fails()) {
            return $this->formatResponse('error', 'validation-error', $validate->errors()->first());
        }

        $content = Content::find($content_id)->update($request->all());
        return $this->formatResponse('success', 'content-create', $content, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

}
