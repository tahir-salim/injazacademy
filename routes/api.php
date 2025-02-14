<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserInterestController;
// use \App\Http\Controllers\DiscussionController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Auth\RegisterController;
use \App\Http\Controllers\Auth\ResetPasswordController;
use \App\Http\Controllers\MentorController;
use \App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */
//Auth
Route::post('/login', [LoginController::class, 'login']);
Route::post('/sign-up', [RegisterController::class, 'signUpRequest']);
Route::post('/socialLogin', [RegisterController::class, 'socialLogin']);
Route::post('/resend-code', [RegisterController::class, 'resendCode']);
Route::post('/verify-code', [RegisterController::class, 'verifyEmail']);

//reset Password
Route::post('/reset-password', [ResetPasswordController::class, 'index']);
Route::post('/reset-password/verify', [ResetPasswordController::class, 'verify']);
Route::post('/reset-password/store', [ResetPasswordController::class, 'store']);

//get Countries
Route::get('/countries', [CountryController::class, 'index']);
// Route::get('/area', [UserController::class, 'getArea']);
// Route::get('/schools/{id}', [UserController::class, 'getSchools']);

Route::middleware('auth:sanctum')->group(function () {

    // Category Controller Api Start
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/tags', [TagController::class, 'tags']);

    //Search Controller
    Route::get('/search', [\App\Http\Controllers\SearchController::class, 'search']);

    //Primary Program Controller
    //Home Tab Api's
    Route::get('/init', [\App\Http\Controllers\ProgramController::class, 'init']);
    Route::get('/programs', [\App\Http\Controllers\ProgramController::class, 'index']);
    Route::get('/programs/explore', [\App\Http\Controllers\ProgramController::class, 'explore']);
    Route::get('/programs/recommended', [\App\Http\Controllers\ProgramController::class, 'recommendedPrograms']);
    Route::get('/program/{program_id}', [\App\Http\Controllers\ProgramController::class, 'show']);
    Route::get('/program/user/resume-courses', [\App\Http\Controllers\ProgramController::class, 'resumeCourses']); // why?
    //Home Tab Api's Ends

    //Favourite Program Api start
    Route::post('/program/favourite', [\App\Http\Controllers\FavouriteProgramController::class, 'store']);
    //Favourite Program Api End

    //Discussion Api Start
    Route::post('/discussion/create-post', [\App\Http\Controllers\DiscussionController::class, 'store']);
    Route::post('/discussion/store', [\App\Http\Controllers\DiscussionController::class, 'storeReply']);
    Route::get('/program/{program_id}/discussion', [\App\Http\Controllers\DiscussionController::class, 'discussionProgram']);
    Route::post('/program/discussion/replies', [\App\Http\Controllers\DiscussionController::class, 'discussionReplies']);
    Route::post('/discussion/like-post', [\App\Http\Controllers\DiscussionController::class, 'likePost']);
    Route::post('/discussion/un-pin', [\App\Http\Controllers\DiscussionController::class, 'unPinPost']);
    Route::post('/discussion/delete-discussion', [\App\Http\Controllers\DiscussionController::class, 'deletePost']);
    Route::post('/discussion/action-on-discussion', [\App\Http\Controllers\DiscussionController::class, 'actionOnDiscussion']);
    //Discussion Api End

    //View and Search Mentor and  Followers and follow/unFollow mentor
    Route::get('/mentor/{user_id}/followers', [MentorController::class, 'mentorFollowers']);
    Route::get('/mentor/{user_id}/follow-mentor', [MentorController::class, 'followMentor']);
    Route::get('/mentor/{user_id}/view-mentor', [MentorController::class, 'viewMentor']);
    Route::post('/mentor/view-student-courses-projects', [MentorController::class, 'viewStudentCoursesProjects']);
    Route::get('/mentor/search', [MentorController::class, 'searchMentor']);

    //Mentor Request Api end
    Route::post('/mentor-request/new', [\App\Http\Controllers\MentorRequestController::class, 'store']);
    Route::post('/mentor-request/status', [\App\Http\Controllers\MentorRequestController::class, 'checkRequestStatus']);
    //Mentor Rquest Api end

    //user Intersest
    Route::get('/user/user-interest', [UserInterestController::class, 'userInterest']);
    Route::post('/user/update-user-interest', [UserInterestController::class, 'updateUserInterest']);
    // user
    Route::get('/user/user-programs', [UserController::class, 'userPrograms']);
    Route::get('/user/mentor-programs', [UserController::class, 'mentorPrograms']);
    Route::post('/user/update-profile', [UserController::class, 'updateProfile']);
    Route::post('/user/upload-avatar', [UserController::class, 'uploadProfileImage']);
    //follow and following
    Route::get('/user/following', [UserController::class, 'following']);
    //Save fcm_token and APP_version
    Route::post('/user/app-details', [UserController::class, 'saveAppDetail']);
    //like discusion or task polymorphic realtion
    Route::post('/user/like/{id}', [UserController::class, 'likes']);

    //Task Api Start
    Route::post('/task/upload', [\App\Http\Controllers\TaskController::class, 'uploadTaskFile']);
    Route::post('/task/{task_id}/mark', [\App\Http\Controllers\TaskController::class, 'markTask']);
    Route::get('/tasks/program/{program_id}', [\App\Http\Controllers\TaskController::class, 'taskAll']);
    Route::post('/enrollment/project/like', [\App\Http\Controllers\TaskController::class, 'likeProject']);
    Route::post('/enrollment/project/view', [\App\Http\Controllers\TaskController::class, 'viewProject']);

    //Task Api End

    //Watch Content Api
    Route::post('/watchcontent/watch', [\App\Http\Controllers\WatchContentController::class, 'markedVideoWatched']);
    Route::get('/watchcontent/watch-history', [\App\Http\Controllers\WatchContentController::class, 'watchHistory']);
    Route::get('/watchcontent/program/{programID}/enrollments', [\App\Http\Controllers\WatchContentController::class, 'enrollments']);

    //Quiz
    Route::get('/test/{program_id}/quiz', [\App\Http\Controllers\TestController::class, 'show']);
    Route::post('/test/quiz/submit', [\App\Http\Controllers\TestController::class, 'submitTest']);

    //Certificate Api start
    Route::post('/enrollments/enroll', [\App\Http\Controllers\EnrollmentsController::class, 'store']);
    Route::get('/enrollments/view-certificates', [\App\Http\Controllers\EnrollmentsController::class, 'certificateList']);
    Route::get('/enrollments/download-certificate', [\App\Http\Controllers\EnrollmentsController::class, 'certificateDownload']);
    Route::post('/enrollments/get-certificate', [\App\Http\Controllers\EnrollmentsController::class, 'getCertificate']);
    Route::post('/enrollments/review', [\App\Http\Controllers\EnrollmentsController::class, 'reviewProgram']);

    //Certificate Api end
    Route::get('/task-history', [\App\Http\Controllers\TaskHistoryController::class, 'getPendingTasks']);
    Route::post('/task-history/view-project', [\App\Http\Controllers\TaskHistoryController::class, 'viewPendingProject']);
    Route::post('/task-history/mark-discussion', [\App\Http\Controllers\TaskHistoryController::class, 'markPendingDiscussion']);

    //Notifications Api
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'notifications']);
    Route::post('/notifications/read/{notification_id}', [\App\Http\Controllers\NotificationController::class, 'readNotification']);
    Route::post('/notifications/toggle-notification', [\App\Http\Controllers\NotificationController::class, 'toggleNotification']);

    //Logout User
    Route::post('/logout', [LoginController::class, 'logout']);

    Route::get('/faq', [FaqController::class, 'index']);
    Route::post('/faq/mark-helpful', [FaqController::class, 'markHelpful']);
});
