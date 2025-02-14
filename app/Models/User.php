<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use \App\Models\Enrollment;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable, SoftDeletes;

    const ROLE_ADMIN = 1;
    const ROLE_MENTOR = 2;
    const ROLE_STUDENT = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'user_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'dob' => 'datetime',
        'phone' => 'int',
        'last_activity_at' => 'datetime'
    ];

    protected $appends = [
        'name'
    ];

    //Mentor Types
    const MAIN = 'MAIN';
    const ASSISTANT = 'ASSISTANT';

    public static function MENTOR_TYPES()
    {
        return [
            static::MAIN => ucwords(strtolower(str_replace('_', ' ', static::MAIN))),
            static::ASSISTANT => ucwords(strtolower(str_replace('_', ' ', static::ASSISTANT))),
        ];
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        $name = Str::title($this->first_name);
        if($this->middle_name){
            $name .= " ".Str::title($this->middle_name);
        }
        if($this->last_name){
            $name .= " ".Str::title($this->last_name);
        }
        return $name;
    }

    /**
     * Get the user's app active status.
     *
     * @return bool
     */
    public function getActiveInAppAttribute()
    {
        return $this->last_activity_at ? $this->last_activity_at->diffInDays(now()) <= 90 : false;
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, UserInterest::class);
    }
    public function favouritedPrograms()
    {
        return $this->hasMany(FavouriteProgram::class, 'user_id');
    }
    public function programs()
    {
        return $this->belongsToMany(Program::class, FavouriteProgram::class);
    }
    public function mentorPrograms()
    {
        return $this->belongsToMany(Program::class, ProgramMentor::class, 'mentor_id', 'program_id');
    }
    public function userPrograms()
    {
        return $this->belongsToMany(Program::class, Enrollment::class, 'user_id', 'program_id');
    }
    public function likes()
    {
        return $this->hasMany(Like::class, 'user_id');
    }
    public function likedTasks()
    {
        return $this->likes()->where('likeable_type', Enrollment::class);
    }
    public function discussions()
    {
        return $this->hasMany(Discussion::class, 'user_id');
    }
    public function likedDiscussions()
    {
        return $this->likes()->where('likeable_type', Discussion::class);;
    }
    public function likedTasksList()
    {
        return $this->morphedByMany(Enrollment::class, 'likeable', Like::class, 'user_id', 'likeable_id')->where('is_like',1);
    }
    public function likedDiscussionsList()
    {
        return $this->morphedByMany(Discussion::class, 'likeable', Like::class, 'user_id', 'likeable_id')->where('is_like',1);
    }
    public function contents()
    {
        return $this->belongsToMany(Content::class, WatchContent::class);
    }
    public function followers()
    {
        return $this->hasMany(Follower::class);
    }
    public function task()
    {
        return $this->belongsToMany(Task::class, UserInterest::class);
    }
    public function userTag()
    {
        return $this->belongsToMany(Tag::class, UserInterest::class)->withTimestamps();
    }
    public function actionBy()
    {
        return $this->belongsTo(MentorRequest::class, 'action_by', 'id');
    }

    public function mentorRequest()
    {
        return $this->hasOne(MentorRequest::class, 'email', 'email');
    }

    public function scopeIsMentorUser($query)
    {
        return $query->where('role_id', Role::MENTOR);
    }
    public function scopeActive($query)
    {
        return $query->where('is_blocked', false);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function isMentor()
    {
        return $this->role_id == Role::MENTOR;
    }
    public function isStudent()
    {
        return $this->role_id == Role::STUDENT;
    }
    public function isAdmin()
    {
        return $this->role_id == Role::ADMIN;
    }

    public function scopeSearch($query, $search, $table = '')
    {
        if($table != '')
            $table .= '.';
        return $query->when($search, function ($q) use ($search, $table) {
            $q->where(function ($query) use ($search, $table) {
                $query->where($table.'first_name', 'LIKE', "%" . $search . "%")
                      ->orWhere($table.'last_name', 'LIKE', "%" . $search . "%")
                      ->orWhere($table.'middle_name', 'LIKE', "%" . $search . "%");
            });
        });
    }

    public function scopeIsFollowing($query)
    {
        return $query->withCount(['followers as isFollowing' => function ($q) {
            $q->where('follower_id', Auth::id());
        }]);
    }

    public function scopeFollowersCount($query)
    {
        return $query->withCount(['followers']);
    }

    public function scopeActiveInApp($query)
    {
        return $query->whereDate('last_activity_at', '>=', now()->subDays(90));
    }
    public function scopeInActiveInApp($query)
    {
        return $query->where(function($q){
            $q
                ->whereDate('last_activity_at', '<', now()->subDays(90))
                ->orWhereNull('last_activity_at');
        });
    }

}
