<?php


use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/test-download', function(){
    return Storage::disk('s3')->download('certificate/file/enrollment-358.pdf');
});


Route::get('/pdfview', function () {


    $data = [
        'name' => 'Ashar Ayub مرحبا',
        'date' => date('m/d/Y'),
        'program' => 'مرحبا'
    ];

    $pdf = \niklasravnsborg\LaravelPdf\Facades\Pdf::loadView('certificate', $data);

    return $pdf->stream('pdfview.pdf');
});

Route::get('/program/{program_id}', [\App\Http\Controllers\ProgramController::class, 'shareProgram']);


// Route::get('/notify', function(){
//     PushNotification::Pusher(
//         'Workshops Live Now',
//         'New workshops have gone live just now',
//         null,
//         null,
//         [
//             'programIds' => '49, 46'
//         ],
//         false,
//         'UpcomingWorkshops',
//     );
// });


