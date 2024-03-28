<?php

declare(strict_types=1);

namespace App\Helper;

interface GeneratePasswordInterfaceHelper
{
    /**
     * @param int $length
     *
     * @return string
     */
    public static function generatePassword(int $length): string;

    /**
     * @param $entityInstance
     *
     * @return void
     */
    public function createAccount($entityInstance): void;
}
