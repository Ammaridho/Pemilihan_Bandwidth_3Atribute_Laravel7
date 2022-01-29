<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class adminPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //untuk memberi hak akses admin
    //dapat menambah pola
    public function adminPolicy(User $user)
    {
        $user->username == 'adminUtama';
    }
}
