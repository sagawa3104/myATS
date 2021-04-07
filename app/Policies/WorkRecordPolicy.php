<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkRecord;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkRecordPolicy
{
    use HandlesAuthorization;


    public function before(User $user, $ability)
    {
        // $auth_user = Auth::user();
        if ((bool)$user->is_admin) {
            return true;
        }
        $request = App('request');
        $uri_user = $request->route()->parameter('user');
        return $uri_user->id == $user->id;
    }

    /**
     * Determine whether the user can view any work records.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the work record.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WorkRecord  $workRecord
     * @return mixed
     */
    public function view(User $user, WorkRecord $workRecord)
    {
        //
    }

    /**
     * Determine whether the user can create work records.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the work record.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WorkRecord  $workRecord
     * @return mixed
     */
    public function update(User $user, WorkRecord $workRecord)
    {
        //
    }

    /**
     * Determine whether the user can delete the work record.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WorkRecord  $workRecord
     * @return mixed
     */
    public function delete(User $user, WorkRecord $workRecord)
    {
        //
    }

    /**
     * Determine whether the user can restore the work record.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WorkRecord  $workRecord
     * @return mixed
     */
    public function restore(User $user, WorkRecord $workRecord)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the work record.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WorkRecord  $workRecord
     * @return mixed
     */
    public function forceDelete(User $user, WorkRecord $workRecord)
    {
        //
    }
}
