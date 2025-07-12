<?php

namespace Modules\Classes\Http\facades;

use Illuminate\Support\Facades\Facade;

class ClassesFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'classes.service';
    }
}
