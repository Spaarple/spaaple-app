<?php

declare(strict_types=1);

namespace App\Enum;

use App\Traits\EnumTrait;

enum Integration: string
{
    use EnumTrait;

    case MAILCHIMP = 'MailChimp';
    case CALENDLY = 'Calendly (Prise de rendez-vous)';
    case MAILJET = 'MailJet';
    case STRIPE = 'Stripe (Paiement)';
    case MATOMO = 'Matomo (Statistiques)';
    case GOOGLE_ANALYTICS = 'Google Analytics (Statistiques)';
}
