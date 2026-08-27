<?php

namespace App\Enum\Utils;

use App\Traits\EnumFunctions;

enum ToastType: string
{
    use EnumFunctions;
    case DEFAULT = 'default'; 
    case WARNING   = 'warning'; #aviso
    case INFO   = 'info'; #informação
    case SUCCESS   = 'success'; #sucesso
    case ERROR   = 'error'; #erro
}