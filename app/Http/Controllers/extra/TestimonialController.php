<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TestimonialController extends Controller
{
    public function create(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'body' => 'required|string',
            'enrollment_id' => 'required|int',
        ]);
        if ($validate->fails()) {
            return $this->formatResponse('error', 'validation-error', $validate->errors()->first());
        }

        $testimonial = new Testimonial();
        $testimonial->enrollment_id = $request->enrollment_id;
        $testimonial->body = $request->body;
        $testimonial->is_approved = false;
        $testimonial->save();
        return $this->formatResponse('success', 'testimonial-inserted');
    }

    public function show($enrollment_id)
    {
        $testimonial = testimonial::where('enrollment_id', $enrollment_id)->get();
        return $this->formatResponse('success', 'testimonial-show', $testimonial);

    }
    public function update(request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'body' => 'required|string',
        ]);
        if ($validate->fails()) {
            return $this->formatResponse('error', 'validation-error', $validate->errors()->first());
        }

        $testimonial = Testimonial::find($id);
        $testimonial->enrollment_id = $request->enrollment_id;
        $testimonial->body = $request->body;
        $testimonial->is_approved = $request->is_approved;
        $testimonial->save();
        return $this->formatResponse('success', 'testimonial-updated');

        $testimonial = testimonial::where('enrollment_id', $id);
        return $this->formatResponse('success', 'discussion', $testimonial);

    }
}
