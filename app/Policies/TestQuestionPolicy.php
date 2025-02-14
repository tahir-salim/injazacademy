<?php

namespace App\Policies;

use App\Models\TestQuestion;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TestQuestionPolicy
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
     * @param  \App\Models\TestQuestion  $testQuestion
     * @return mixed
     */
    public function view(User $user, TestQuestion $testQuestion)
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
     * @param  \App\Models\TestQuestion  $testQuestion
     * @return mixed
     */
    public function update(User $user, TestQuestion $testQuestion)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TestQuestion  $testQuestion
     * @return mixed
     */
    public function delete(User $user, TestQuestion $testQuestion)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TestQuestion  $testQuestion
     * @return mixed
     */
    public function restore(User $user, TestQuestion $testQuestion)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TestQuestion  $testQuestion
     * @return mixed
     */
    public function forceDelete(User $user, TestQuestion $testQuestion)
    {
        //
    }
}
