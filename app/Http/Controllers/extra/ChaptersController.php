<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChaptersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $chapter = Chapter::all();
        return $this->formatResponse('success', 'chapter-get', $chapter, 200);

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
            'sub_title' => 'required|string',
            'body' => 'required|string',
            'order_number' => 'required|int',
            'program_id' => 'required|int',
            'promo_video' => '',
            'status' => 'required|string',
            'program_id' => 'required|int',

        ]);
        if ($validate->fails()) {
            return $this->formatResponse('error', 'validation-error', $validate->errors()->first());
        }

        $chapter = chapter::create($request->all());
        return $this->formatResponse('success', 'chapter-create', $chapter, 200);

        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($chapter_id)
    {
        $chapter = Chapter::find($chapter_id);
        return $this->formatResponse('success', 'chapter-get', $chapter, 200);

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
            'order_number' => 'required|int',
            'program_id' => 'required|int',
            'promo_video' => '',
            'status' => 'required|string',
            'program_id' => 'required|int',

        ]);
        if ($validate->fails()) {
            return $this->formatResponse('error', 'validation-error', $validate->errors()->first());
        }

        $chapter = chapter::find($id)->update($request->all());
        return $this->formatResponse('success', 'chapter-update', $chapter, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

}
