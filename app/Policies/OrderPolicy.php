<?php

namespace App\Policies;

use App\Models\Users;
use App\Models\Orders;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    use HandlesAuthorization;

    public function create(Users $user): Response
    {
        // Verificar si el usuario tiene el rol adecuado para crear una orden
        return $user->role === 'admin' || $user->role === 'user'
            ? Response::allow()
            : Response::deny('No tienes permiso para crear una orden.');
    }

    public function updateStatus(Users $user, Orders $order): Response
    {
        // Verificar si el usuario es admin o si es el propietario de la orden
        return $user->role === 'admin'
        ? Response::allow()
        : Response::deny('No tienes permiso para modificar una orden.');

    }

}
