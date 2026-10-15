<?php

namespace App\Enum;

use App\Traits\EnumFunctions;

enum AccountType: string
{
    use EnumFunctions;
    case RECEIVABLE   = 'receivable'; #conta a receber
    case PAYABLE   = 'payable'; #conta a pagar
}
