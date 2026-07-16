<?php

namespace App\Enum;

use App\Traits\EnumFunctions;

enum TypeUser: string
{
    use EnumFunctions;
    case CUSTOMER   = 'customer'; #cliente
    case OWNER   = 'owner'; #dono
    case DEVELOPER   = 'developer'; #desenvolvedor
}
