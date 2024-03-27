<?php

declare(strict_types=1);

namespace App\Enum;

use App\Traits\EnumTrait;

enum Integration: string
{
    use EnumTrait;

    case EMAIL = 'Solution d\'email';
    case PAYMENT = 'Solution de paiement en ligne';
    case ANALYTICS = 'Analyse de données (trafics, ventes, etc)';
    case AGENDA = 'Prise de rendez-vous en ligne';
    case OTHER = 'Autres intégrations';
}
