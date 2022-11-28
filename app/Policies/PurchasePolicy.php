<?php

namespace Crater\Policies;

use Crater\Models\Purchase;
use Crater\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Silber\Bouncer\BouncerFacade;

class PurchasePolicy
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
        if (BouncerFacade::can('view-purchase', Purchase::class)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Crater\Models\User  $user
     * @param  \Crater\Models\Purchase $purchase
     * @return mixed
     */
    public function view(User $user, Purchase $purchase)
    {
        if (BouncerFacade::can('view-purchase', $purchase) && $user->hasCompany($purchase->company_id)) {
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
        if (BouncerFacade::can('create-purchase', Purchase::class)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Crater\Models\User  $user
     * @param  \Crater\Models\Purchase $purchase
     * @return mixed
     */
    public function update(User $user, Purchase $purchase)
    {
        if (BouncerFacade::can('edit-purchase', $purchase) && $user->hasCompany($purchase->company_id)) {
            // return $debit->allow_edit;
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Crater\Models\User  $user
     * @param  \Crater\Models\Purchase $purchase
     * @return mixed
     */
    public function delete(User $user, Purchase $purchase)
    {
        if (BouncerFacade::can('delete-purchase', $purchase) && $user->hasCompany($purchase->company_id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Crater\Models\User  $user
     * @param  \Crater\Models\Purchase $purchase
     * @return mixed
     */
    public function restore(User $user, Purchase $purchase)
    {
        if (BouncerFacade::can('delete-purchase', $purchase) && $user->hasCompany($purchase->company_id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Crater\Models\User  $user
     * @param  \Crater\Models\Purchase  $purchase
     * @return mixed
     */
    public function forceDelete(User $user, Purchase $purchase)
    {
        if (BouncerFacade::can('delete-purchase', $purchase) && $user->hasCompany($purchase->company_id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can send email of the model.
     *
     * @param  \Crater\Models\User  $user
     * @param  \Crater\Models\Purchase $purchase
     * @return mixed
     */
    public function send(User $user, Purchase $purchase)
    {
        if (BouncerFacade::can('send-purchase', $purchase) && $user->hasCompany($purchase->company_id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete models.
     *
     * @param  \Crater\Models\User $user
     * @return mixed
     */
    public function deleteMultiple(User $user)
    {
        if (BouncerFacade::can('delete-purchase', Purchase::class)) {
            return true;
        }

        return false;
    }
}
