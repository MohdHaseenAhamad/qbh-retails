<?php

namespace Crater\Policies;

use Crater\Models\Category;
use Crater\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Silber\Bouncer\BouncerFacade;

class CategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \Crater\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        if (BouncerFacade::can('view-category', Category::class)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Crater\Models\User  $user
     * @param  \Crater\Models\Category  $category
     * @return mixed
     */
    public function view(User $user, Category $category)
    {
        if (BouncerFacade::can('view-category', $category)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \Crater\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if (BouncerFacade::can('create-category', Category::class)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Crater\Models\User  $user
     * @param  \Crater\Models\Category  $category
     * @return mixed
     */
    public function update(User $user, Category $category)
    {
        if (BouncerFacade::can('edit-category', $category)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Crater\Models\User  $user
     * @param  \Crater\Models\Category  $category
     * @return mixed
     */
    public function delete(User $user, Category $category)
    {
        if (BouncerFacade::can('delete-category', $category)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Crater\Models\User  $user
     * @param  \Crater\Models\Category  $category
     * @return mixed
     */
    public function restore(User $user, Category $category)
    {
        if (BouncerFacade::can('delete-category', $category)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Crater\Models\User  $user
     * @param  \Crater\Models\Category  $category
     * @return mixed
     */
    public function forceDelete(User $user, Category $category)
    {
        if (BouncerFacade::can('delete-category', $category)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete models.
     *
     * @param  \Crater\Models\User  $user
     * @return mixed
     */
    public function deleteMultiple(User $user)
    {
        if (BouncerFacade::can('delete-category', Category::class)) {
            return true;
        }

        return false;
    }
}
