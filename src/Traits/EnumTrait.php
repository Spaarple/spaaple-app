<?php

declare(strict_types=1);

namespace App\Traits;

trait EnumTrait
{
    /**
     * Return an array with enumeration's item name mapped to its value.
     *
     * Example:
     *      enum Role: string
     *      {
     *          use EnumTrait;
     *
     *          case ROLE_USER = 'utilisateur';
     *          case ROLE_FREELANCE = 'freelance';
     *      }
     *
     *      Role::asArray(); // ['ROLE_USER' => 'utilisateur, 'ROLE_FREELANCE' => 'freelance']
     *
     * @return array
     */
    public static function asArray(): array
    {
        return array_merge(...array_map(static fn ($item) => [$item->name => $item->value], self::cases()));
    }

    /**
     * Return an array with enumeration's item value mapped to its name.
     *
     * Example:
     *      enum Role: string
     *      {
     *          use EnumTrait;
     *
     *          case ROLE_USER = 'utilisateur';
     *          case ROLE_FREELANCE = 'freelance';
     *      }
     *
     *      Role::asArrayInverted(); // ['utilisateur' => 'ROLE_USER, 'freelance' => 'ROLE_FREELANCE']
     *
     * @return array
     */
    public static function asArrayInverted(): array
    {
        return array_merge(...array_map(static fn ($item) => [$item->value => $item->name], self::cases()));
    }
}
