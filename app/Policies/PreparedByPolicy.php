<?php

namespace Crater\Policies;

use Crater\Models\PreparedBy;
use Crater\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Silber\Bouncer\BouncerFacade;

class PreparedByPolicy
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
        // dd('rt');
        if (BouncerFacade::can('view-item', PreparedBy::class)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Crater\Models\User  $user
     * @param  \Crater\Models\Item  $item
     * @return mixed
     */
    public function view(User $user, PreparedBy $item)
    {
        if (BouncerFacade::can('view-item', $item) && $user->hasCompany($item->company_id)) {
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
        if (BouncerFacade::can('create-item', PreparedBy::class)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Crater\Models\User  $user
     * @param  \Crater\Models\Item  $item
     * @return mixed
     */
    public function update(User $user, PreparedBy $item)
    {
        if (BouncerFacade::can('edit-item', $item) && $user->hasCompany($item->company_id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Crater\Models\User  $user
     * @param  \Crater\Models\Item  $item
     * @return mixed
     */
    public function delete(User $user, PreparedBy $item)
    {
        if (BouncerFacade::can('delete-item', $item) && $user->hasCompany($item->company_id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Crater\Models\User  $user
     * @param  \Crater\Models\Item  $item
     * @return mixed
     */
    public function restore(User $user, PreparedBy $item)
    {
        if (BouncerFacade::can('delete-item', $item) && $user->hasCompany($item->company_id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Crater\Models\User  $user
     * @param  \Crater\Models\Item  $item
     * @return mixed
     */
    public function forceDelete(User $user, PreparedBy $item)
    {
        if (BouncerFacade::can('delete-item', $item) && $user->hasCompany($item->company_id)) {
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
        if (BouncerFacade::can('delete-item', PreparedBy::class)) {
            return true;
        }

        return false;
    }
}
