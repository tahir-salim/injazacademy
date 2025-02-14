<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Program extends Model
{

    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $fillable = [
        'title',
        'sub_title',
        'body',
        'promo_video',
        'duration',
        'age_from',
        'age_to',
        'is_workshop',
        'is_live',
        'live_date_time',
        'status',
        'generate_linkedin_certificate',
        'issue_certificate',
        'task_required',
        'task',
        'available_languages',
        'live_link',
    ];

    protected $attributes = [];

    // status
    const DRAFT = 'DRAFT';
    const PUBLISHED = 'PUBLISHED';
    const FINISHED = 'FINISHED';
    const DISCONTINUED = 'DISCONTINUED';
    // Languages
    const ENGLISH = 'English';
    const ARABIC = 'Arabic';

    protected $casts = [
        'live_date_time' => 'datetime',
        'live_date_time_end' => 'datetime',
        'available_languages' => 'array',
    ];

    public static function STATES()
    {
        return [
            static::DRAFT => ucwords(strtolower(str_replace('_', ' ', static::DRAFT))),
            static::PUBLISHED => ucwords(strtolower(str_replace('_', ' ', static::PUBLISHED))),
            static::FINISHED => ucwords(strtolower(str_replace('_', ' ', static::FINISHED))),
            static::DISCONTINUED => ucwords(strtolower(str_replace('_', ' ', static::DISCONTINUED))),
        ];
    }

    public static function ageGroups()
    {
        return [
            '7 - 11' => '7 - 11',
            '12 - 15' => '12 - 15',
            '16 - 18' => '16 - 18',
            '18+' => '18+',
        ];
    }

    public static function durationHours()
    {
        $hours = [
            '0h' => 0
        ];
        foreach (range(1, 50) as $i) {
            $hours[$i . 'h'] = $i . ' hour' . ($i == 1 ? '' : 's');
        }
        return $hours;
    }

    public static function durationMinutes()
    {
        $minutes = [
            '0m' => 0
        ];
        foreach (range(1, 59) as $i) {
            if($i){
                $minutes[$i . 'm'] = $i . ' minute' . ($i == 1 ? '' : 's');
            }
        }
        return $minutes;
    }

    public static function programStates()
    {
        return [
            static::DRAFT => 'In Active',
            static::PUBLISHED => 'Active',
        ];
    }

    public static function LANGUAGES()
    {
        return [
            static::ENGLISH => ucwords((str_replace('_', ' ', static::ENGLISH))),
            static::ARABIC => ucwords((str_replace('_', ' ', static::ARABIC))),
        ];
    }

    public function getHours(){
        if($this->duration){
            $durations = explode(' ',$this->duration);
            return $durations[0];
        }
        return null;
    }

    public function getMinuntes(){
        if($this->duration){
            $durations = explode(' ',$this->duration);
            return $durations[1];
        }
        return '0 m';
    }

    public function calculateAge(){
        if ($this->age_group) {
            if ($this->age_group === '18+') {
                $this->age_from = 19;
                $this->age_to = 999;
            } else {
                $ages = explode(' - ', $this->age_group);
                $this->age_from = $ages[0];
                $this->age_to = $ages[1];
            }
        }
    }

    public function calculateDuration(){
        if ($this->is_workshop) {
            $minutes = Carbon::parse($this->live_date_time)->diffInMinutes(Carbon::parse($this->live_date_time_end));
            $hours = (int) floor($minutes/60);
            $minutes = $minutes%60;
            $this->duration = $hours . 'h ' . $minutes . 'm';
        } elseif(isset($this->duration_minutes)){
            $duration = ($this->duration_hours && $this->duration_hours != 0 ? $this->duration_hours : '') . ($this->duration_minutes && $this->duration_minutes ? ' ' . $this->duration_minutes : '');
            unset($this->duration_hours);
            unset($this->duration_minutes);
            $this->duration = $duration;
        }
    }

    /**
     * Appendend Attributes
     */

    public function getRemainingTimeAttribute()
    {
        $remainingSeconds =  optional($this->live_date_time)->diffInSeconds(now()->subMinute());
        if($remainingSeconds <= 900 && $this->live_date_time > now())
            return $remainingSeconds;
        return null;
    }

    /********************** */

    public function chapters()
    {
        return $this->hasMany(Chapter::class, 'program_id')->orderBy('order_number');
    }

    public function chapter()
    {
        return $this->chapters()->where('status', self::PUBLISHED)->orderBy('chapters.order_number')->withTrashed();
    }

    public function enrollment()
    {
        return $this->hasMany(Enrollment::class, 'program_id');
    }

    public function discussion()
    {
        return $this->hasMany(Discussion::class);
    }

    public function parentDiscussions()
    {
        return $this->discussion()->parents();
    }

    public function studentProject()
    {
        return $this->enrollment()->where('enrollments.project_submitted', true);
    }

    public function myEnrollment()
    {
        return $this->enrollment()->where('user_id', Auth::id());
    }

    public function test()
    {
        return $this->hasOne(Test::class);
    }

    public function FavouritedUsers()
    {
        return $this->belongsToMany(User::class, FavouriteProgram::class);
    }

    public function favouritedUserPrograms()
    {
        return $this->hasMany(FavouriteProgram::class, 'program_id');
    }

    public function myFavouritedUserPrograms()
    {
        return $this->favouritedUserPrograms()->where('user_id', Auth::id());
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, ProgramCategory::class);
    }
    public function sponsors()
    {
        return $this->belongsToMany(Sponsor::class, ProgramSponsor::class)->withTimestamps();
    }

    public function mentors()
    {
        return $this->belongsToMany(User::class, ProgramMentor::class, 'program_id', 'mentor_id')->withTrashed()->withPivot('mentor_type');
    }

    public function mainMentor()
    {
        return $this->mentors()->wherePivot('mentor_type', User::MAIN);
    }

    // not exist dummy for nova only
    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    /**
     * Same as above relation but created to use foreign key of id instead of mentor _id
     */
    // public function mentorClone()
    // {
    //     return $this->belongsTo(ProgramMentor::class, 'mentor_id');
    // }

    public function authUserPrograms()
    {
        return $this->mentors()->where('users.id', Auth::id());
    }

    public function watchHistory()
    {
        return $this->hasMany(WatchContent::class);
    }

    public function content()
    {
        return $this->hasMany(Content::class)->where('status', Program::PUBLISHED);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, ProgramTag::class, 'program_id', 'tag_id')->withTimestamps();
    }

    public function isFavourite()
    {
        return $this->FavouritedUsers()->where('user_id', Auth::id());
    }

    public function isEnrolled()
    {
        return $this->enrollment()->auth();
    }

    public function scopeCourses($query)
    {
        return $query->where('programs.is_workshop', 0);
    }

    public function scopeWorkshops($query)
    {
        return $query->where('programs.is_workshop', 1);
    }

    public function scopePublished($query)
    {
        return $query->where(function ($q) {
            $q->where('programs.status', '=', static::PUBLISHED)
                ->orWhere('programs.status', '=', static::FINISHED);
        });
    }

    public function scopePublishedPrograms($query)
    {
        return $query->published()
            ->courses()
            ->addProgramExtraParam()
            ->mainMentor()
            ->whereHas('mainMentor')
            ->with('sponsors')
            ->addFavouriteParam();
    }

    public function scopePublishedWorkshops($query)
    {
        return $query->published()
            ->workshops()
            ->whereHas('mainMentor')
            ->mainMentor()
            ->addFavouriteParam()
            ->notEndedWorkshops();
            // ->where('live_date_time_end','>', now());
    }

    public function scopeNotEndedWorkshops($query)
    {
        return $query->where('live_date_time_end','>', now());
    }

    public function scopeMainMentor($query){
        return $query->with(['mentors' => function($q){
            $q->wherePivot('mentor_type', User::MAIN)
                ->isFollowing();
        }]);
    }

    public function scopeAddProgramExtraParam($query)
    {
        return $query->withCount(['chapter', 'enrollment', 'FavouritedUsers']);
    }

    public function scopeAddFavouriteParam($query)
    {
        return $query->withCount('isFavourite as isFavourite');
    }

    public function scopeWhereCategory($query, $categoryId)
    {
        return $query->when($categoryId, function ($q) use ($categoryId) {
            $q->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        });
    }

    public function scopeWhereTags($query, $tagIds)
    {
        return $query->whereHas('tags', function ($q) use ($tagIds) {
            $q->whereIn('tags.id', $tagIds);
        });
    }

    public function scopeSearch($query, $search, $withMentor = false)
    {
        if($withMentor)
            return $query->when($search, function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                ->orWhereHas('mentors', function($q) use($search){
                    $q->search($search, 'users');
                });
            });
        return $query->when($search, function ($q) use ($search) {
            $q->where('title', 'like', "%$search%");
        });
    }

    public function scopeWhereAge($query, $age)
    {
        return $query->where([
            ['age_to', '>=', $age],
            ['age_from', '<=', $age],
        ]);
    }

    public function scopeWhereTagFilter($query, $tag)
    {
        return $query->when($tag == 'favourite', function ($q) {
            $q->whereHas('myFavouritedUserPrograms');
        })
            ->when($tag == 'live', function ($q) {
                $q->where('is_live', 1)
                    ->orderBy('live_date_time', 'DESC');
            })
            ->when($tag == 'upcoming', function ($q) {
                $q->whereBetween('live_date_time', [Carbon::now(), Carbon::now()->addDays(14)])
                    ->orderBy('live_date_time', 'asc');
            })
            ->when($tag == 'top', function ($q) {
                $q->withCount(['enrollment', 'FavouritedUsers'])
                    ->orderBy('enrollment_count', 'desc')
                    ->orderBy('favourited_users_count', 'desc')
                    ->orderBy('created_at', 'desc');
            })
            ->when($tag == 'new', function ($q) {
                $q->where('updated_at', '>', Carbon::now()->subDays(20));
                $q->orderBy('updated_at', 'desc');
            })
            ->when($tag == null, function ($q) {
                $q->latest();
            });
    }

    public function scopeWithCommomAttributesCount($query)
    {
        $query->withCount([
            'myEnrollment as me_enrolled',
            'isFavourite as isFavourite'
        ]);
    }
}
