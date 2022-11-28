<?php

namespace Crater\Policies;

use Crater\Models\Store;
use Crater\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Silber\Bouncer\BouncerFacade;

class StorePolicy
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
        if (BouncerFacade::can('view-store', Store::class)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Crater\Models\User $user
     * @param  \Crater\Models\Store $store
     * @return mixed
     */
    public function view(User $user, Store $store)
    {
        if (BouncerFacade::can('view-store', $store)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \Crater\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        if (BouncerFacade::can('create-store', Store::class)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Crater\Models\User $user
     * @param  \Crater\Models\Store $store
     * @return mixed
     */
    public function update(User $user, Store $store)
    {
        if (BouncerFacade::can('edit-store', $store)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Crater\Models\User $user
     * @param  \Crater\Models\Store $store
     * @return mixed
     */
    public function delete(User $user, Store $store)
    {
        if (BouncerFacade::can('delete-store', $store)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Crater\Models\User $user
     * @param  \Crater\Models\Store $store
     * @return mixed
     */
    public function restore(User $user, Store $store)
    {
        if (BouncerFacade::can('delete-store', $store)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Crater\Models\User $user
     * @param  \Crater\Models\Store $store
     * @return mixed
     */
    public function forceDelete(User $user, Store $store)
    {
        if (BouncerFacade::can('delete-store', $store)) {
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
        if (BouncerFacade::can('delete-store', Store::class)) {
            return true;
        }

        return false;
    }
}
