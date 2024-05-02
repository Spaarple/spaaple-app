<?php

declare(strict_types=1);

namespace App\Enum;

use App\Traits\EnumTrait;

enum CMS: string
{
    use EnumTrait;

    case AUCUN = 'Création personnalisée du site';
    case SHOPIFY = 'Plateforme de commerce en ligne prête à l\'emploi (Shopify)';
    case WORDPRESS = 'Outil de création de site web simple et populaire (Wordpress)';
}
