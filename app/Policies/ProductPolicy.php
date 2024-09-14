<?php

namespace App\Policies;

use App\Models\Users;
use App\Models\Products;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    use HandlesAuthorization;

    public function create(Users $user): Response
    {
        return $user->role === 'admin'
        ? Response::allow()
        : Response::deny('No tienes permiso para crear el producto.');
    }

    public function update(Users $user, Products $product): Response
    {
        return $user->role === 'admin'
        ? Response::allow()
        : Response::deny('No tienes permiso para modificar el producto.');
    }

    public function delete(Users $user, Products $product): Response
    {
        return $user->role === 'admin'
        ? Response::allow()
        : Response::deny('No tienes permiso para eliminar el producto.');
    }
}