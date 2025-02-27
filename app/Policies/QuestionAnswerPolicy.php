<?php

namespace App\Policies;

use App\Models\QuestionAnswer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestionAnswerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\QuestionAnswer  $questionAnswer
     * @return mixed
     */
    public function view(User $user, QuestionAnswer $questionAnswer)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\QuestionAnswer  $questionAnswer
     * @return mixed
     */
    public function update(User $user, QuestionAnswer $questionAnswer)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\QuestionAnswer  $questionAnswer
     * @return mixed
     */
    public function delete(User $user, QuestionAnswer $questionAnswer)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\QuestionAnswer  $questionAnswer
     * @return mixed
     */
    public function restore(User $user, QuestionAnswer $questionAnswer)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\QuestionAnswer  $questionAnswer
     * @return mixed
     */
    public function forceDelete(User $user, QuestionAnswer $questionAnswer)
    {
        //
    }
}
