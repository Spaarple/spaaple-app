<?php

declare(strict_types=1);

namespace App\Enum;

use App\Traits\EnumTrait;

enum Role: string
{
    use EnumTrait;

    case ROLE_ADMINISTRATOR = 'administrateur';
    case ROLE_CLIENT = 'client';
    case ROLE_USER = 'utilisateur';
}
