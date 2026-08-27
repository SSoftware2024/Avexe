<?php

namespace App\Enum\Utils;

use App\Traits\EnumFunctions;

enum DialogAlertType: string
{
    use EnumFunctions;
    case WARNING   = 'warning'; #aviso
    case INFO   = 'info'; #informação
    case SUCCESS   = 'success'; #sucesso
    case ERROR   = 'error'; #erro
}