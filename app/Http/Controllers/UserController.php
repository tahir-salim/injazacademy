<?php

namespace App\Http\Controllers;

use App\Models\Discussion;
use App\Models\Enrollment;
use App\Models\Follower;
use App\Models\Like;
use App\Models\Program;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function userPrograms(Request $request)
    {
        $limit = $request->query('limit') ?: 10;
        $typeId = $request->query('type_id') ?: 'workshops';

        if (!in_array($typeId, ['workshops', 'courses'])) {
            return $this->formatResponse('error', 'type-not-exists');
        }

        $programs = Enrollment::auth()
            ->when($typeId == 'courses', function ($q) {
                $q->has('program')->with(['program' => function ($q) {
                    $q->addProgramExtraParam()
                        ->addFavouriteParam()
                        ->mainMentor();
                }])
                    ->whereRelation('program', 'is_workshop', false);
            })
            ->when($typeId == 'workshops', function ($q) {
                $q->whereHas('program' , function($q){
                    $q->notEndedWorkshops();
                })->with(['program' => function ($q) {
                    $q->addProgramExtraParam()
                        ->mainMentor()
                        ->notEndedWorkshops();
                }])
                ->whereRelation('program', 'is_workshop', true);
            })
            ->paginate($limit);

        return $this->formatResponse('success', $typeId == 'workshops' ? 'user-workshops' : 'user-courses', [
            'programs' => $programs,
        ]);
    }



    public function mentorPrograms(Request $request)
    {
        $limit = $request->query('limit') ?: 10;
        $typeId = $request->query('program_type') ?: 0;

        if (!in_array($typeId, [0, 1])) {
            return $this->formatResponse('error', 'type-not-exists');
        }

        $programs = Program::whereHas('authUserPrograms')
            ->when($typeId == 0, function ($q) {
                $q->courses();
                $q->published();
            })
            ->when($typeId == 1, function ($q) {
                $q->workshops();
                $q->published();
            })
            ->withCount('enrollment')
            ->paginate($limit);

        return $this->formatResponse('success', $typeId == 1 ? 'user-workshops' : 'user-courses', [
            'programs' => $programs,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'first_name' => 'required|string',
            'middle_name' => 'string',
            'last_name' => 'required|string',
            'ar_name' => 'required|string',
            'country_id' => 'required',
            'cpr' => 'required|min:5|max:10',
            'dob' => 'required|date',
            'gender' => 'required',
            'phone' => 'required',
            'company' => '',
            'occupation' => '',
            'experience' => '',
            'brief' => '',
        ]);
        if ($validate->fails()) {
            return $this->formatResponse(
                'error',
                'validation-error',
                $validate->errors()->first()
            );
        }

        $user = User::find(Auth::id());
        $user->first_name = $request->first_name;
        $user->middle_name = $request->middle_name;
        $user->last_name = $request->last_name;
        $user->ar_name = $request->ar_name;
        $user->email = $request->email;
        $user->country_id = $request->country_id;
        $user->dob = $request->dob;
        $user->cpr = $request->cpr;
        $user->gender = $request->gender;
        $user->phone = $request->phone;
        if($user->role_id == Role::STUDENT)
        {
            $user->area_id = $request->area_id;
            $user->school_id = $request->school_id;
            $user->area_name = $request->area_name;
            $user->school_name = $request->school_name;
        }

        if ($request->has('brief')) {
            $user->brief = $request->brief;
        }

        if ($request->has('company')) {
            $user->company = $request->company;
        }

        if ($request->has('occupation')) {
            $user->occupation = $request->occupation;
        }

        if ($request->has('experience')) {
            $user->experience = $request->experience;
        }

        $user->save();
        return $this->formatResponse('success', 'profile-updated', $user, 200);
    }

    public function uploadProfileImage(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'file' => 'required|image',
        ]);

        if ($validate->fails()) {
            return $this->formatResponse(
                'error',
                'validation-error',
                $validate->errors(),
                400
            );
        }

        $path = Storage::disk('s3')->put('images/users', new File($request->file));
        $user = Auth::user();
        $user->avatar = $path;
        $user->save();
        return $this->formatResponse('success', 'successfully-uploaded', $user);
    }

    public function following()
    {
        $followers = Follower::with('followed')
            ->whereHas('followed')
            ->where('follower_id', Auth::id())
            ->paginate(20);
        return $this->formatResponse('success', 'follower-list', $followers, 200);
    }

    public function likes(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'likeable_type' => 'required',
            'is_like' => 'required|boolean',
        ]);
        if ($validate->fails()) {
            return $this->formatResponse(
                'error',
                'validation-error',
                $validate->errors()->first()
            );
        }

        $likeType = '';
        if ($request->likeable_type === Like::TASK) {
            $likeType = Task::find($id);
        }

        if ($request->likeable_type === Like::DISCUSSION) {
            $likeType = Discussion::find($id);
        }

        if (!$likeType) {
            return $this->formatResponse(
                'error',
                'validation-error',
                'this-is-not-likeable-type'
            );
        }

        $like = new Like();
        $like->likeable()->associate($likeType);
        $like->user_id = Auth::id();
        $like->likeable_id = $id;
        $like->is_like = $request->is_like;
        $like->save();

        return $this->formatResponse('success', $request->likeable_type . ' Liked!');
    }

    public function saveAppDetail(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'fcm_token' => 'nullable',
            'app_version' => 'nullable',
            'device_info' => 'required',
        ]);

        if ($validate->fails()) {
            return $this->formatResponse(
                'error',
                'validation-error',
                $validate->errors()->first()
            );
        }

        $user = User::find(Auth::id());
        if ($request->fcm_token) {
            $user->fcm_token = $request->fcm_token;
        }
        if ($request->app_version) {
            $user->app_version = $request->app_version;
        }
        $user->device_id = $request->device_info['deviceId'];
        $user->device_type = $request->device_info['deviceType'];
        $user->last_activity_at = now();
        $user->save();

        return $this->formatResponse(
            'success',
            'app-details-saved-for-user',
        );
    }

    // public static function getArea()
    // {
    //     $client = new \GuzzleHttp\Client();

    //     $apiCallUrl = 'https://injaz.klabs.co/api/area';

    //     try {
    //         $response = $client->request('GET' , $apiCallUrl);

    //          return Cache::remember('area',now()->addDays(7), function() use ($response){ return array_column(json_decode((string)$response->getBody())->data, 'name' , 'id'); });
    //         // $usersWithoutFulllDetails = json_decode((string) $response->getBody())->users;
    //     } catch (\Throwable $th) {
    //         return $th;
    //     }
    // }

    // public static function getSchools($id)
    // {
    //     if(!isset($id))
    //     {
    //         return [ 'status' =>  'error', 'message' => 'area-id-not-provided' ];
    //     }

    //     $client = new \GuzzleHttp\Client();

    //     $apiCallUrl = 'https://injaz.klabs.co/api/area/'.$id.'/school';

    //     try {
    //         $response = $client->request('GET' , $apiCallUrl);

    //         return json_decode((string)$response->getBody())->data;
    //     } catch (\Throwable $th) {
    //         return $th;
    //     }
    // }
}
