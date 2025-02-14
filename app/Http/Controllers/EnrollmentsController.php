<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\CalculateAvgRatingJob;
use App\Models\Enrollment;
use App\Services\CertificateService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EnrollmentsController extends Controller
{

    protected $certificateService;

    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'program_id' => 'required',
        ]);
        if ($validate->fails()) {
            return $this->formatResponse('error', 'validation-error', $validate->errors()->first());
        }

        $enrollment = new Enrollment();
        $enrollment->user_id = Auth::id();
        $enrollment->program_id = $request->program_id;
        $enrollment->status = Enrollment::ACTIVE;
        $enrollment->started_date = now();
        $enrollment->is_all_content_watched = false;
        $enrollment->project_submission_no = 0;
        $enrollment->save();
        return $this->formatResponse('success', 'enrollment-create', $enrollment, 200);
    }

    public function getCertificate(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'enrollment_id' => 'required',
        ]);

        if ($validate->fails()) {
            return $this->formatResponse('error', 'validation-error', $validate->errors()->first());
        }

        $enrollment = Enrollment::active()
            ->withProgramTitle()
            ->find($request->enrollment_id);

        if(!$enrollment){
            return $this->formatResponse('error', 'enrollment-does-not-exists');
        }

        if(!$enrollment->is_certified)
            $this->certificateService->generateCertificate(Auth::user()->name, $enrollment, 'enrollment-' . $enrollment->id);
        // $enrollment->is_certified = true;
        // $enrollment->finished_date = Carbon::now();
        // $enrollment->certification_date = Carbon::now();
        // $enrollment->certificate_url = $paths['filePath'];
        // $enrollment->certificate_image = $paths['imagePath'];
        // $enrollment->save();

        return $this->formatResponse('success', 'certificate-issued-get', $enrollment->certificate_image);
    }

    public function certificateList()
    {
        $certificate = Enrollment::CertifiedAuthEnrollments()
            ->withProgramTitle()
            ->orderBy('certification_date', 'desc')
            ->get();

        // $certificate = $certificate->toArray();
        foreach($certificate as $cert)
        {
            $cert->certificate_url_temp = Storage::disk('s3')->temporaryUrl($cert->certificate_url, now()->addMinutes(5));
        }
        return $this->formatResponse('success', 'view-certificate', $certificate, 200);
    }

    public function certificateDownload(Request $request)
    {
        if(!$request->query('certificate_path'))
            return $this->formatResponse('error', 'no-file-spceified');
        return Storage::disk('s3')->download($request->query('certificate_path'));
    }

    public function reviewProgram(Request $request){
        $validate = Validator::make($request->all(), [
            'enrollment_id' => 'required',
            'review' => 'required|int',
            'testimonial' => 'required|string'
        ]);

        if ($validate->fails()) {
            return $this->formatResponse('error', 'validation-error', $validate->errors()->first());
        }

        $enrollment = Enrollment::certifiedAuthEnrollments()
            ->find($request->enrollment_id);

        if(!$enrollment){
            return $this->formatResponse('error', 'enrollment-does-not-exists');
        }

        $enrollment->review = $request->review;
        $enrollment->testimonial = $request->testimonial;
        $enrollment->is_review = true;
        $enrollment->save();

        dispatch(new CalculateAvgRatingJob($enrollment->program_id));

        return $this->formatResponse('success', 'review-added', $enrollment);
    }
}
