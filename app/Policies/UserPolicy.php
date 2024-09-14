<?php

namespace App\Policies;

use App\Models\Users;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    public function create(Users $user): Response
    {
        return $user->role === 'admin'
        ? Response::allow()
        : Response::deny('No tienes permiso para crear el usuario.');
    }

    public function update(Users $user): Response
    {
        return $user->role === 'admin'
        ? Response::allow()
        : Response::deny('No tienes permiso para modificar el usuario.');
    }

    public function delete(Users $user): Response
    {
        return $user->role === 'admin'
        ? Response::allow()
        : Response::deny('No tienes permiso para eliminar el usuario.');
    }
}