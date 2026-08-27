<?php

namespace App\Facades;

use App\Utils\DialogAlertFacade;
use Illuminate\Support\Facades\Facade;

final class DialogAlert extends Facade
{
    protected static function getFacadeAccessor(){
          return DialogAlertFacade::class;
      }
}
