<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class CertificateService {

    public function __construct()
    {
        //
    }

    public function generateCertificate($name, $enrollment, $certificateName)
    {
        $data = [
            'name' => $name,
            'date' => date('m/d/Y'),
            'program' => $enrollment->program->title
        ];

        $pdf = \niklasravnsborg\LaravelPdf\Facades\Pdf::loadView('certificate', $data);
        // $pdf->addInfo(config('pdf'));
        $pdf->save(public_path('temp\aatr.pdf'));

        $pdf = new \Spatie\PdfToImage\Pdf(public_path('temp\aatr.pdf'));
        $pdf->saveImage(public_path('temp\aatr.png'));

        $imagePath = Storage::disk('s3')->putFileAs('certificate/images', new File(public_path('temp\aatr.png')), $certificateName.'.png');
        $filePath = Storage::disk('s3')->putFileAs('certificate/file', new File(public_path('temp\aatr.pdf')), $certificateName.'.pdf');

        Storage::disk('custom')->delete('aatr.pdf');
        Storage::disk('custom')->delete('aatr.png');

        //Update Enrollment
        $enrollment->is_certified = true;
        $enrollment->finished_date = Carbon::now();
        $enrollment->certification_date = Carbon::now();
        $enrollment->certificate_url = $filePath;
        $enrollment->certificate_image = $imagePath;
        $enrollment->save();

        return [
            'imagePath' => $imagePath,
            'filePath' => $filePath
        ];
    }
}
