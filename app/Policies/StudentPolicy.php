<?php

namespace App\Policies;

use App\Models\Enrollment;
use App\Models\Program;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, User $model)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, User $model)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, User $model)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }

    public function attachAnyTag(User $user)
    {
        return false;
    }

    public function attachAnyProgram(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can detach a tag from a podcast.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $program
     * @param  \App\Models\Program  $mentor
     * @return mixed
     */
    public function attachProgram(User $user, User $mentor, Program $program)
    {
        if ($program->mentors->contains('id', $mentor->id)) {
            return false;
        } else {
            return $this->attachAnyProgram($user);
        }
    }

    public function attachAnyEnrollment(User $user)
    {
        return false;
    }

    public function attachEnrollment(User $user, User $student, Enrollment $enrollement)
    {
        if ($enrollement->projectLikes->contains('user_id', $student->id)) {
            return false;
        } else {
            return $this->attachAnyEnrollment($user);
        }
    }


    public function attachAnyTask(User $user)
    {
        return false;
    }

    public function attachAnyDiscussion(User $user)
    {
        return false;
    }
}
