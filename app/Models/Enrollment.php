<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Enrollment extends Model
{

    use HasFactory, SoftDeletes;

    protected $guarded = [];

    // status
    const ACTIVE = 'ACTIVE';
    const COMPLETED = 'COMPLETED';
    const CANCELLED = 'CANCELLED';

    public static function STATUSES()
    {
        return [
            static::ACTIVE => ucwords(strtolower(str_replace('_', ' ', static::ACTIVE))),
            static::COMPLETED => ucwords(strtolower(str_replace('_', ' ', static::COMPLETED))),
            static::CANCELLED => ucwords(strtolower(str_replace('_', ' ', static::CANCELLED))),
        ];
    }

    protected $appends = [
        'project_pass',
        'test_pass'
    ];

    public function getProjectPassAttribute(){
        return $this->review_score >= 50;
    }

    public function getTestPassAttribute(){
        if(optional($this->program)->project_required)
            return $this->test_score >= optional(optional($this->program)->test)->passing_criteria;
        return false;
    }

    protected $fillable = [
        'user_id',
        'program_id',
        'status',
        'started_date',
        'finished_date',
    ];

    protected $casts = [
        'started_date' => 'datetime',
        'finished_date' => 'datetime',
        'certification_date' => 'datetime',
    ];

    public function taskHistory()
    {
        return $this->morphMany(TaskHistory::class, 'historable');
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function program()
    {
        return $this->belongsTo(Program::class)->withTrashed();
    }

    public function questionAnswers()
    {
        return $this->belongsToMany(QuestionAnswer::class, UserAnswer::class, 'enrollment_id', 'answer_id');
    }

    public function dicussions()
    {
        return $this->hasMany(Discussion::class, 'enrollment_id');
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class, 'enrollment_id');
    }

    public function task()
    {
        return $this->hasMany(Task::class, 'enrollment_id')->orderBy('tasks.identifier_no','desc');
    }

    public function currentTasks()
    {
        return $this->task()->where('tasks.identifier_no', $this->project_submission_no);
    }

    public function previousTasks()
    {
        return $this->task()->where('tasks.identifier_no', '<>', $this->project_submission_no);
    }

    public function project()
    {
        return $this->hasOne(self::class, 'id');
    }

    public function projectLikes()
    {
        return $this->morphMany(Like::class, 'likeable')->where('is_like' , true);
    }

    public function projectLikedByMe()
    {
        return $this->projectLikes()->where([
            ['user_id' , '=' , Auth::id()],
            ['is_like' , '=' , true]
        ]);
    }

    public function projectViews()
    {
        return $this->hasMany(UserTaskView::class);
    }

    public function projectViewedByMe()
    {
        return $this->projectViews()->where('user_id' , Auth::id());
    }

    // public function hasAllVideosBeenWatch()
    // {
    //     $chapters = DB::table('chapters')
    //         ->select('id')
    //         ->where('program_id', $this->program_id)
    //         ->whereNull('deleted_at')
    //         ->pluck('id')
    //         ->toArray();

    //     $videos = DB::table('contents')
    //         ->select('id')
    //         ->whereNull('deleted_at')
    //         ->whereIn('chapter_id', $chapters)
    //         ->get()
    //         ->pluck('id')
    //         ->toArray();

    //     $watchedVideo = DB::table('watch_contents')
    //         ->selectRaw('content_id AS id')
    //         ->where('user_id', Auth::id())
    //         ->get()
    //         ->pluck('id')
    //         ->toArray();
    //     return count(array_diff($videos, $watchedVideo)) ? false : true;
    // }

    public function scopePrograms($query)
    {
        return $query->whereHas('program', function($q){
            $q->courses();
        });
    }

    public function scopeWorkshops($query)
    {
        return $query->whereHas('program', function($q){
            $q->workshops();
        });
    }

    public function scopeCertified($query)
    {
        return $query->where('is_certified', true);
    }

    public function scopeReviewed($query)
    {
        return $query->where('enrollments.is_review', true);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::ACTIVE)->whereHas('program');
    }

    public function scopeAuth($query)
    {
        return $query->where('enrollments.user_id', Auth::id());
    }

    public function scopeWithProgramTitle($query)
    {
        return $query->with('program:id,title,sub_title,program_image');
    }

    public function scopeCertifiedAuthEnrollments($query)
    {
        return $query->active()
            ->certified()
            ->auth();
    }

    /**
     * Best practice new work start form here
     */

    public function scopeTaskByProgram($query, $program_id){
        $query->whereRelation('task', 'program_id', $program_id);
    }

    public function scopeWithAppends($query)
    {
        $query->with(['task', 'user'])->withProgramTitle();
    }

    // public function scopeWithUser($query)
    // {
    //     $query->with('user');
    // }

    public function scopeWhereByProgram($query, $program_id)
    {
        $query->where('program_id', $program_id);
    }

    // public function scopeWithProgram($query)
    // {
    //     $query->with('program:id,title,sub_title,program_image');
    // }


    public function scopeOrderByNewlyUpdated($query)
    {
        $query->orderBy('updated_at', 'desc');
    }

    public function scopeAppendCountables($query)
    {
        $query->withCount('projectLikes', 'projectLikedByMe', 'projectViews', 'projectViewedByMe');
    }


    /**
     * DB Logic Functions
     */

    // public function getEnrollmentWithTask($user_type, $limit = 10 , $with_review_score = false) : LengthAwarePaginator
    // {
    //     return $this->taskByProgram()
    //          ->withTask()
    //          ->withUser()
    //          ->withProgramTitle()
    //          ->when($user_type == 0, function($q){
    //              $q->appendCountables();
    //          })
    //          ->when(!$with_review_score, function($q){
    //              $q->whereNull('review_score');
    //          })
    //          ->when($with_review_score, function($q){
    //              $q->whereNotNull('review_score');
    //          })
    //          ->orderByNewlyUpdated()
    //          ->paginate($limit);
    // }

}
