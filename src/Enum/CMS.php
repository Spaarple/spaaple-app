<?php

declare(strict_types=1);

namespace App\Enum;

use App\Traits\EnumTrait;

enum CMS: string
{
    use EnumTrait;

    case SHOPIFY = 'Shopify';
    case WORDPRESS = 'Wordpress';
    case AUCUN = 'Aucun CMS - Je veux un site sur mesure';
}
