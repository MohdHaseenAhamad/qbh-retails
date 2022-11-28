<?php

namespace Crater\Policies;

use Crater\Models\Credit;
use Crater\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Silber\Bouncer\BouncerFacade;

class CreditPolicy
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
        if (BouncerFacade::can('view-credit-note', Credit::class)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Crater\Models\User  $user
     * @param  \Crater\Models\Credit  $credit
     * @return mixed
     */
    public function view(User $user, Credit $credit)
    {
        if (BouncerFacade::can('view-credit-note', $credit) && $user->hasCompany($credit->company_id)) {
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
        if (BouncerFacade::can('create-credit-note', Credit::class)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Crater\Models\User  $user
     * @param  \Crater\Models\Credit $credit
     * @return mixed
     */
    public function update(User $user, Credit $credit)
    {
        if (BouncerFacade::can('edit-credit-note', $credit) && $user->hasCompany($credit->company_id)) {
            // return $credit->allow_edit;
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Crater\Models\User  $user
     * @param  \Crater\Models\Credit  $credit
     * @return mixed
     */
    public function delete(User $user, Credit $credit)
    {
        if (BouncerFacade::can('delete-credit-note', $credit) && $user->hasCompany($credit->company_id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Crater\Models\User  $user
     * @param  \Crater\Models\Credit  $credit
     * @return mixed
     */
    public function restore(User $user, Credit $credit)
    {
        if (BouncerFacade::can('delete-credit-note', $credit) && $user->hasCompany($credit->company_id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Crater\Models\User  $user
     * @param  \Crater\Models\Credit  $credit
     * @return mixed
     */
    public function forceDelete(User $user, Credit $credit)
    {
        if (BouncerFacade::can('delete-credit-note', $credit) && $user->hasCompany($credit->company_id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can send email of the model.
     *
     * @param  \Crater\Models\User  $user
     * @param  \Crater\Models\Payment  $payment
     * @return mixed
     */
    public function send(User $user, Credit $credit)
    {
        if (BouncerFacade::can('send-credit-note', $credit) && $user->hasCompany($credit->company_id)) {
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
        if (BouncerFacade::can('delete-credit-note', Credit::class)) {
            return true;
        }

        return false;
    }
}
