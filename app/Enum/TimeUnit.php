<?php

namespace App\Enum;

use App\Traits\EnumFunctions;

enum TimeUnit: string
{
    use EnumFunctions;
    case DAY   = 'day'; #dia
    case HOUR   = 'hour'; #hora
    case MINUTE   = 'minute'; #minuto
}
