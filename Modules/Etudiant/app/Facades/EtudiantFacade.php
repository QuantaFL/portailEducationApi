<?php

namespace Modules\Etudiant\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array createStd(array $data)
 * @see \Modules\Etudiant\app\Services\EtudiantService
 */
class EtudiantFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'etudiant_service';
    }

}
