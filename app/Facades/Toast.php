<?php

namespace App\Facades;

use App\Utils\ToastFacade;
use Illuminate\Support\Facades\Facade;

final class Toast extends Facade
{
    protected static function getFacadeAccessor(){
          return ToastFacade::class;
      }
}