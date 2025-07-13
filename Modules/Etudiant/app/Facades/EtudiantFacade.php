<?php

namespace Modules\Etudiant\Facades;

use Illuminate\Support\Facades\Facade;

class EtudiantFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'etudiant_service';
    }
}
