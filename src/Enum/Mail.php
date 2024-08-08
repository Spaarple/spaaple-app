<?php

declare(strict_types=1);

namespace App\Enum;

use App\Traits\EnumTrait;

enum Mail: string
{
    use EnumTrait;

    case SUPPORT = 'support@spaarple.fr';
    case CONTACt = 'contact@spaarple.fr';
    case MAXIME = 'mlejeune@spaarple.fr';
    case LOUKA = 'louka.millon@spaarple.fr';
}
