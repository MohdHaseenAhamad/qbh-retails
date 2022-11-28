<?php

namespace Crater\Policies;

use Crater\Models\Company;
use Crater\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SettingsPolicy
{
    use HandlesAuthorization;

    public function manageCompany(User $user, Company $company)
    {
        if ($user->id == $company->owner_id) {
            return true;
        }

        // CHECK FOR MANAGE COMPANY PERMISSION
        // FOR NORMAL USER
        return $this->checkForAdditionalPermission($user->id, $company->id, 'manage-company');
    }

    // public function manageBank(User $user, Company $company)
    // {
    //     if ($user->id == $company->owner_id) {
    //         return true;
    //     }

    //     // CHECK FOR MANAGE COMPANY PERMISSION
    //     // FOR NORMAL USER
    //     return $this->checkForAdditionalPermission($user->id, $company->id, 'manage-bank');
    // }

    public function checkForAdditionalPermission($user_id, $company_id, $ability){
        // GET ABILITY ID
        $ability_id = \DB::table('abilities')->where('name', $ability)->value('id');

        // GET ASSGINED ROLE AND SCOPE ID
        $assigned_role = \DB::table('assigned_roles')->where('entity_id', $user_id)->where('entity_type', 'Crater\Models\User')->first();

        // CHECK FOR PERMISSION
        $isAllowed = \DB::table('permissions')->whereScope($assigned_role->scope)->where('entity_id', $assigned_role->role_id)->exists();

        // dd($isAllowed);
        return $isAllowed;
    }

    public function manageBackups(User $user)
    {
        if ($user->isOwner()) {
            return true;
        }

        return false;
    }

    public function manageFileDisk(User $user)
    {
        if ($user->isOwner()) {
            return true;
        }

        return false;
    }

    public function manageEmailConfig(User $user)
    {
        if ($user->isOwner()) {
            return true;
        }

        return false;
    }
}
