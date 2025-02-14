<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProgramControllers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $program = Program::all();
        return $this->formatResponse('success', 'programs-get', $program, 200);
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
            'promo_video' => 'required|string',
            'duration' => 'required|string',
            'age_from' => 'required|int',
            'age_to' => 'required|int',
            'is_workshop' => '',
            'is_live' => '',
            'live_date_time' => '',
            'status' => '',
            'generate_linkedin_certificate' => '',
            'issue_certificate' => '',
            'task_required' => '',
            'task' => '',
            'available_languages' => 'required',
            'live_link' => '',
        ]);
        if ($validate->fails()) {
            return $this->formatResponse(
                'error',
                'validation-error',
                $validate->errors()->first()
            );
        }

        $program = Program::create($request->all());
        return $this->formatResponse('success', 'program-inserted', $program, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $program = Program::find($id);
        return $this->formatResponse('success', 'program-get', $program, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $program_id)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required|string',
            'sub_title' => 'required|string',
            'body' => 'required|string',
            'promo_video' => 'required|string',
            'duration' => 'required|string',
            'age_from' => 'required|int',
            'age_to' => 'required|int',
            'is_workshop' => '',
            'is_live' => '',
            'live_date_time' => '',
            'status' => '',
            'generate_linkedin_certificate' => '',
            'issue_certificate' => '',
            'task_required' => '',
            'task' => '',
            'available_languages' => 'required',
            'live_link' => '',
        ]);
        if ($validate->fails()) {
            return $this->formatResponse(
                'error',
                'validation-error',
                $validate->errors()->first()
            );
        }

        $program = Program::find($program_id)->update($request->all());
        return $this->formatResponse('success', 'program-updated', $program, 200);
    }

}
