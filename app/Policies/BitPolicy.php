<?php

namespace App\Policies;

use App\Bit;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BitPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any bits.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the bit.
     *
     * @param  \App\User  $user
     * @param  \App\Bit  $bit
     * @return mixed
     */
    public function view(User $user, Bit $bit)
    {
        return $user->id == $bit->user_id;
    }

    /**
     * Determine whether the user can create bits.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the bit.
     *
     * @param  \App\User  $user
     * @param  \App\Bit  $bit
     * @return mixed
     */
    public function update(User $user, Bit $bit)
    {
        return $user->id == $bit->user_id;
    }

    /**
     * Determine whether the user can delete the bit.
     *
     * @param  \App\User  $user
     * @param  \App\Bit  $bit
     * @return mixed
     */
    public function delete(User $user, Bit $bit)
    {
        //
    }

    /**
     * Determine whether the user can restore the bit.
     *
     * @param  \App\User  $user
     * @param  \App\Bit  $bit
     * @return mixed
     */
    public function restore(User $user, Bit $bit)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the bit.
     *
     * @param  \App\User  $user
     * @param  \App\Bit  $bit
     * @return mixed
     */
    public function forceDelete(User $user, Bit $bit)
    {
        //
    }
}
