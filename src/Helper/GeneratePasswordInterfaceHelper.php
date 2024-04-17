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
    public function generatePassword(int $length): string;

    /**
     * @param $entityInstance
     * @param string $generatePassword
     * @return void
     */
    public function sendMailAdmin($entityInstance, string $generatePassword): void;
}
