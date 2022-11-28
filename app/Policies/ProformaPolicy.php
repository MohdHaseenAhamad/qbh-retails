<?php

namespace Crater\Policies;

use Crater\Models\Proforma;
use Crater\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Silber\Bouncer\BouncerFacade;

class ProformaPolicy
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
        if (BouncerFacade::can('view-proforma', Proforma::class)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Crater\Models\User  $user
     * @param  \Crater\Models\Proforma  $proforma
     * @return mixed
     */
    public function view(User $user, Proforma $proforma)
    {
        if (BouncerFacade::can('view-proforma', $proforma) && $user->hasCompany($proforma->company_id)) {
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
        if (BouncerFacade::can('create-proforma', Proforma::class)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Crater\Models\User  $user
     * @param  \Crater\Models\Proforma  $proforma
     * @return mixed
     */
    public function update(User $user, Proforma $proforma)
    {
        if (BouncerFacade::can('edit-proforma', $proforma) && $user->hasCompany($proforma->company_id)) {
            return $proforma->allow_edit;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Crater\Models\User  $user
     * @param  \Crater\Models\proforma  $proforma
     * @return mixed
     */
    public function delete(User $user, Proforma $proforma)
    {
        if (BouncerFacade::can('delete-proforma', $proforma) && $user->hasCompany($proforma->company_id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Crater\Models\User  $user
     * @param  \Crater\Models\Proforma  $proforma
     * @return mixed
     */
    public function restore(User $user, Proforma $proforma)
    {
        if (BouncerFacade::can('delete-proforma', $proforma) && $user->hasCompany($proforma->company_id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Crater\Models\User  $user
     * @param  \Crater\Models\proforma  $proforma
     * @return mixed
     */
    public function forceDelete(User $user, Proforma $proforma)
    {
        if (BouncerFacade::can('delete-proforma', $proforma) && $user->hasCompany($proforma->company_id)) {
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
    public function send(User $user, Proforma $proforma)
    {
        if (BouncerFacade::can('send-proforma', $proforma) && $user->hasCompany($proforma->company_id)) {
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
        if (BouncerFacade::can('delete-proforma', Proforma::class)) {
            return true;
        }

        return false;
    }
}
