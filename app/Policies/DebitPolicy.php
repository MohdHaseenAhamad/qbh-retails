<?php

namespace Crater\Policies;

use Crater\Models\Debit;
use Crater\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Silber\Bouncer\BouncerFacade;

class DebitPolicy
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
        if (BouncerFacade::can('view-debit-note', Debit::class)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Crater\Models\User  $user
     * @param  \Crater\Models\Debit  $debit
     * @return mixed
     */
    public function view(User $user, Debit $debit)
    {
        if (BouncerFacade::can('view-debit-note', $debit) && $user->hasCompany($debit->company_id)) {
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
        if (BouncerFacade::can('create-debit-note', Debit::class)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Crater\Models\User  $user
     * @param  \Crater\Models\Debit $debit
     * @return mixed
     */
    public function update(User $user, Debit $debit)
    {
        if (BouncerFacade::can('edit-debit-note', $debit) && $user->hasCompany($debit->company_id)) {
            // return $debit->allow_edit;
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Crater\Models\User  $user
     * @param  \Crater\Models\Debit $debit
     * @return mixed
     */
    public function delete(User $user, Debit $debit)
    {
        if (BouncerFacade::can('delete-debit-note', $debit) && $user->hasCompany($debit->company_id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Crater\Models\User  $user
     * @param  \Crater\Models\Debit $debit
     * @return mixed
     */
    public function restore(User $user, Debit $debit)
    {
        if (BouncerFacade::can('delete-debit-note', $debit) && $user->hasCompany($debit->company_id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Crater\Models\User  $user
     * @param  \Crater\Models\Debit  $debit
     * @return mixed
     */
    public function forceDelete(User $user, Debit $debit)
    {
        if (BouncerFacade::can('delete-debit-note', $debit) && $user->hasCompany($debit->company_id)) {
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
    public function send(User $user, Debit $debit)
    {
        if (BouncerFacade::can('send-debit-note', $debit) && $user->hasCompany($debit->company_id)) {
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
        if (BouncerFacade::can('delete-debit-note', Debit::class)) {
            return true;
        }

        return false;
    }
}
