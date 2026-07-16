<?php

namespace App\Enum;

use App\Traits\EnumFunctions;

enum DayOfWeek: string
{
    use EnumFunctions;
    case SUNDAY   = 'sunday'; #domingo
    case MONDAY   = 'monday'; #segunda
    case TUESDAY   = 'tuesday'; #terça
    case WEDNESDAY   = 'wednesday'; #quarta
    case THURSDAY   = 'thursday'; #quinta
    case FRIDAY   = 'friday'; #sexta
    case SATURDAY   = 'saturday'; #sábado
}
