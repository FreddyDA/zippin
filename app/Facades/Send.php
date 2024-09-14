<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Send extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'send';
    }
}