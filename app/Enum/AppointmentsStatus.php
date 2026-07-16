<?php

namespace App\Enum;

use App\Traits\EnumFunctions;

enum AppointmentsStatus: string
{
    use EnumFunctions;
    case WAITING   = 'waiting'; #aguardando
    case CONFIRMED   = 'confirmed'; #confirmado
    case CANCELLED   = 'cancelled'; #cancelado
}
