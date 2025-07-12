<?php

namespace Modules\AnneAcademique\Facades;

use Illuminate\Support\Facades\Facade;

class AnneeAcademiqueFacade extends Facade
{
    protected static function getFacadeAccessor(): string
       {
           return 'annee.academique.service';
       }

}
