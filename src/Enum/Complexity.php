<?php

declare(strict_types=1);

namespace App\Enum;

use App\Traits\EnumTrait;

enum Complexity: string
{
    use EnumTrait;

    case SIMPLE = 'Site vitrine, Application simple';
    case MIDDLE = 'Application avec des fonctionnalités plus ou moins complexes';
    case HARDCORE = 'Application métier complexe avec des interactions & fonctionnalités spécifiques';
}
