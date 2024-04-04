<?php

declare(strict_types=1);

namespace App\Enum;

use App\Traits\EnumTrait;

enum NumberPage: string
{
    use EnumTrait;

    case SMALL = '< 5 pages';
    case MEDIUM = '6 - 20 pages';
    case BIGGEST = 'Plus de 50 pages';
}
