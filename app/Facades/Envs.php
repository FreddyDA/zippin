<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Envs extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'envs';
    }
}