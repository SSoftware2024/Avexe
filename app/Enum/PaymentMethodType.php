<?php

namespace App\Enum;

use App\Traits\EnumFunctions;

enum PaymentMethodType: string
{
    use EnumFunctions;
    case PIX   = 'waiting'; #pix
    case CASH   = 'confirmed'; #dinheiro
    case CREDIT_CARD   = 'cancelled'; #cartão crédito
    case DEBIT_CARD   = 'debit_cart'; #cartão débito
}
