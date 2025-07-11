<?php

namespace Modules\Auth\Http\Facades;

use Illuminate\Support\Facades\Facade;

class AuthFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'auth.service';
    }

}
