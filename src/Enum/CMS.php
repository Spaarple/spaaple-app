<?php

declare(strict_types=1);

namespace App\Enum;

use App\Traits\EnumTrait;

enum CMS: string
{
    use EnumTrait;

    case AUCUN = 'Site sur mesure';
    case SHOPIFY = 'Shopify';
    case WORDPRESS = 'Wordpress';
}
