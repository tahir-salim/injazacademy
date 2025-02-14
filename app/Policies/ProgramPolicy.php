<?php

namespace App\Policies;

use App\Models\Program;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Request;

class ProgramPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public function create(User $user)
    {
        return true;
    }

    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Program  $program
     * @return mixed
     */
    public function view(User $user, Program $program)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Program  $program
     * @return mixed
     */
    public function update(User $user, Program $program)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Program  $program
     * @return mixed
     */
    public function delete(User $user, Program $program)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Program  $program
     * @return mixed
     */
    public function restore(User $user, Program $program)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Program  $program
     * @return mixed
     */
    public function forceDelete(User $user, Program $program)
    {
        //
    }

    public function attachAnyUser(User $user, Program $program)
    {
        return true;
    }

    /**
     * Determine whether the user can detach a tag from a podcast.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Program  $program
     * @param  \App\Models\User  $mentor
     * @return mixed
     */
    public function attachUser(User $user, Program $program, User $mentor)
    {
        if ($program->mentors->contains('id', $mentor->id)) {
            return false;
        } else {
            return $this->attachAnyUser($user, $program);
        }
    }
    /**
     * Determine whether the user can detach a tag from a podcast.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Program  $program
     * @param  \App\Models\User  $mentor
     * @return mixed
     */
    public function detachUser(User $user, Program $program, User $mentor)
    {
        return optional($mentor->pivot)->mentor_type == User::ASSISTANT;
    }
}
