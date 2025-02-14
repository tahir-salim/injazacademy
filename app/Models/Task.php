<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Task extends Model
{
    use HasFactory, HasApiTokens, Notifiable;

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }
    // public function likes()
    // {
    //     return $this->morphMany(Like::class, 'likeable');
    // }

    public function user()
    {
        return $this->belongsToMany(User::class, UserTaskView::class);
    }
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * DB Logic functions ** new **
     */

     public function createTask($enrollment_id, $request, $path)
     {
        $task = new Task();
        $task->enrollment_id = $enrollment_id;
        $task->program_id = $request->program_id;
        $task->views_count = 0;
        $task->show_in_program = false;
        $task->task_image = $path;
        $task->file = $request->file('file')->getClientOriginalName();;
        $task->identifier_no = $request->project_submission_no;
        $task->save();

        return $task;
     }
}
